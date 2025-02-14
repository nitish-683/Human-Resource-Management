<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CandidateDocument;
use App\Notifications\CandidatePolicyLinkNotification;
use App\Notifications\EmployeeDataNotification;
use Illuminate\Support\Facades\Gate;
use App\Models\Candidate;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\StoreCandidateRequest;
use App\Http\Requests\UpdateCandidateRequest;
use App\Models\DocumentType;
use App\Models\Question;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Notifications\NewCandidateNotification;
use App\Notifications\CandidateDocumentUploadNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Crypt;
use ZipArchive;

class CandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if(Gate::denies('candidate_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $candidates = Candidate::latest()->paginate(15);
        return view('admin.candidates.index', compact('candidates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(Gate::denies('candidate_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $documenttype = DocumentType::all();
        return view('admin.candidates.create',compact('documenttype'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCandidateRequest $request)
    {
        $candidate = new Candidate();

        $candidate->name = $request->name;
        $candidate->email = $request->email;
        $candidate->phone = $request->phone;
       // Generate a default password if the user did not provide one
    $password = $request->password ? $request->password : substr($request->name, 0, 4) . substr($request->phone, 0, 4);

    // Hash the password before storing it in the database
    $candidate->password = Hash::make($password);
        if ($candidate->save()) {

            $documentTypes = DocumentType::all();

            $documentTypes = DocumentType::all();
            foreach ($documentTypes as $documentType) {
                $fieldName = str_replace(' ', '_',$documentType->name);

                if ($request->hasFile($fieldName)) {
                    $file = $request->file($fieldName);
                    $fileName = $candidate->name."_".$file->getClientOriginalName();
                    $path = $file->storeAs("documents/{$candidate->id}",$fileName, 's3');

                    $candidate->documents()->create([
                        'document_type_id' => $documentType->id,
                        'document_path' => $path,
                    ]);
                }
            }

            toast('Candidate created successfully.','success');
            $candidate->notify(new NewCandidateNotification($candidate,$password));
            return redirect()->route('admin.candidates.index');
        }
        toast('Candidate create failed!.','error');
        return redirect()->route('admin.users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Candidate $candidate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Candidate $candidate)
    {
        abort_if(Gate::denies('candidate_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $documenttype = DocumentType::all();
        $questions = Question::all();
        return view('admin.candidates.edit', compact('candidate','documenttype'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCandidateRequest $request,$id)
    {
        $candidate = Candidate::find($id);
        $candidate->name = $request->name;
        $candidate->email = $request->email;
        $candidate->phone = $request->phone;
        if ($request->password) {
            $candidate->password = Hash::make($request->password);
        }

        if ($candidate->save()) {
            $documentTypes = DocumentType::all();
            foreach ($documentTypes as $documentType) {
                $fieldName = str_replace(' ', '_',$documentType->name);

                if ($request->hasFile($fieldName)) {
                    $existingDocument = $candidate->documents()
                        ->where('document_type_id', $documentType->id)
                        ->first();

                    if ($existingDocument) {
                        Storage::disk('s3')->delete($existingDocument->document_path);
                        $existingDocument->delete();
                    }

                    $file = $request->file($fieldName);
                    $fileName = $candidate->name.'_'.$file->getClientOriginalName();
                    $path = $file->storeAs("documents/{$candidate->id}",$fileName, 's3');

                    $candidate->documents()->create([
                        'document_type_id' => $documentType->id,
                        'document_path' => $path,
                    ]);
                }
            }

            // if ($request->has('answers')) {
            //     foreach ($request->answers as $questionId => $answer) {
            //         CandidateQuestionAnswer::updateOrCreate(
            //             [
            //                 'candidate_id' => $candidate->id,
            //                 'question_id' => $questionId,
            //             ],
            //             [
            //                 'answer' => $answer,
            //             ]
            //         );

            //     }
            // }
            toast('Candidate updated successfully.','success');
            // $candidate->notify(new NewCandidateNotification($candidate));
            //$candidate->notify(new CandidateDocumentUploadNotification());
            return redirect()->route('admin.candidates.index');
        }

        toast('Candidate update failed.','error');
        return redirect()->route('admin.candidates.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Candidate $candidate)
    {
        abort_if(Gate::denies('candidate_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        User::where(['candidate_id' => $candidate->id])->delete();
        $candidate->delete();
        toast('Candidate deleted successfully.','success');
        return redirect()->route('admin.candidates.index');
    }

    public function showConvertToEmployeeForm(Candidate $candidate)
    {
        abort_if(Gate::denies('permission_makeEmployee'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.candidates.convert', compact('candidate'));
    }

    public function convertToEmployeeSubmit(Request $request, $id)
    {
        $candidate = Candidate::findOrFail($id);

        $request->validate([
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email', 
                'regex:/^[a-zA-Z0-9._%+-]+@(beesolvertechnology\.in|beesolvertechnology\.com)$/'
            ],            
            'employee_code' => 'required|unique:users,employee_code|string|max:255',
            'joining_date' => 'required|date',
        ], [
            'email.regex' => 'The email must be from either beesolvertechnology.in or beesolvertechnology.com.'
        ]);

        if (User::where('candidate_email', $candidate->email)->exists()) {
            return redirect()->route('admin.candidates.index')->with('error', 'User with this email already exists.');
        }

        $user = new User();
        $user->name = $candidate->name;
        $user->candidate_email = $candidate->email;
        $user->email = $request->email;
        $user->phone = $candidate->phone;
        $user->employee_code = $request->employee_code;
        $user->joining_date = $request->joining_date;
        $user->candidate_id = $candidate->id;
        $user->password = $candidate->password;
        $user->save();
        $role = Role::where('name','Employee')->first();
        //$token = Crypt::encryptString($candidate->id);
        $user->assignRole([$role->id]);
        //$candidate->notify(new CandidatePolicyLinkNotification($candidate,$token));
        $user->notify(new EmployeeDataNotification($user));

        return redirect()->route('admin.candidates.index')->with('message', 'Candidate converted to Employee successfully.');
    }

  // Method to verify the document of the candidate
  public function verifyDocument(Request $request, $candidateId)
  {
      // Find the candidate by ID
      $candidate = Candidate::find($candidateId);

      // Check if the candidate exists
      if (!$candidate) {
          return response()->json([
              'success' => false,
              'message' => 'Candidate not found.',
          ], 404);
      }

      // Check if the document is already verified
      if ($candidate->documents_verified == 1) {
          return response()->json([
              'success' => false,
              'message' => 'This candidate\'s documents are already verified.',
          ]);
      }

      // Update the 'documents_verified' field to 1 (verified)
      $candidate->documents_verified = 1;

      try {
          // Save the candidate
          $candidate->save();
          return response()->json([
              'success' => true,
              'message' => 'Documents verified successfully!',
          ]);
      } catch (\Exception $e) {
          return response()->json([
              'success' => false,
              'message' => 'An error occurred while verifying the documents.',
          ]);
      }
  }

  public function downloadAll(Request $request)
  {
     $candidateId = $request->input('candidate_id');
      $documents = CandidateDocument::where('candidate_id', $candidateId)->get();
        $candidate = Candidate::find($candidateId);
      if ($documents->isEmpty()) {
          return response()->json(['message' => 'No documents found for this candidate.'], 404);
      }

      // Create a new ZIP file
    $zip = new ZipArchive();
    $zipFileName = $candidate->name.'_documents_' . $candidateId . '.zip';
    $zipFilePath = storage_path('app/public/' . $zipFileName);

    if ($zip->open($zipFilePath, ZipArchive::CREATE) !== TRUE) {
        return response()->json(['message' => 'Could not create ZIP file.'], 500);
    }

    // Loop through documents and add them to the ZIP
    foreach ($documents as $document) {
        $filePath = $document->document_path;

        if (Storage::disk('s3')->exists($filePath)) {
            $fileContent = Storage::disk('s3')->get($filePath);

            // Add the file to the ZIP archive
            $zip->addFromString(basename($filePath), $fileContent);
        }
    }

    // Close the ZIP archive
    $zip->close();

    // Return the link to the generated ZIP file
    return response()->json([
        'message' => 'Documents ready for download.',
        'zipFileUrl' => url('storage/' . $zipFileName)
    ]);
  }

}
