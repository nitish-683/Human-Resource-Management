<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\DocumentType;
use App\Models\PolicyLead;
use App\Models\Question;
use App\Models\User;
use App\Models\UserQuestionAnswer;
use App\Notifications\CandidatePolicyLinkNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->hasRole('Admin')) {
            // Exclude users with the 'Admin' role from the list
            $users = User::whereDoesntHave('roles', function ($query) {
                $query->whereIn('name', ['Sub Admin','Admin']);
            })->latest()->paginate(15);
        } elseif (auth()->user()->hasRole('Employee')) {
            // Show only the current logged-in user
            $users = User::where('id', auth()->id())->paginate(1);
        }elseif (auth()->user()->hasRole('Sub Admin')) {
            // Show only the current logged-in user
            $users = User::whereDoesntHave('roles', function ($query) {
                $query->whereIn('name', ['Sub Admin','Admin']);
            })->latest()->paginate(15);
        } else {
            abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
        }

        return view('admin.users.index', compact('users'));
    }

     public function subAdminIndex()
    {
        abort_if(Gate::denies('sub_admin_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if (auth()->user()->hasRole('Admin')) {
            // Exclude users with the 'Admin' role from the list
            $users = User::where('id','!=',auth()->user()->id)->whereHas('roles', function ($query) {
                $query->where('name', 'Sub Admin');
            })->latest()->paginate(15);
        } else {
            abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
        }

        return view('admin.users.sub-admin-index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('permission_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.users.create');

    }

        /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function subAdminCreate()
    {
        abort_if(Gate::denies('sub_admin_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.users.sub-admin-create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'name' => 'required|regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8', // Adjust the min length as per your requirements
        ]);

        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;

        if ($user->save()) {
            return redirect()->route('admin.users.index')->with('message', 'User created successfully!');
        }
        return redirect()->route('admin.users.index')->with('message', 'User create failed!');
    }

    public function subAdminStore(Request $request)
    {
        //
        $validatedData = $request->validate([
            'name' => 'required|regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8', // Adjust the min length as per your requirements
        ]);

        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;

        if ($user->save()) {
             if (auth()->user()->hasRole('Admin')) {
                $role = Role::where(['name' => 'Sub Admin'])->first();
                $user->syncRoles([$role->id??1]);
            }
            return redirect()->route('admin.users.subAdmin.index')->with('message', 'Sub-Admin created successfully!');
        }
        return redirect()->route('admin.users.subAdmin.index')->with('message', 'Sub-Admin create failed!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if (auth()->user()->hasRole('Employee') && auth()->id() !== $user->id) {
            abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
        }
        $documenttype = DocumentType::all();
        $questions = Question::all();
        $roles = Role::all();
        return view('admin.users.edit', compact('user','documenttype','questions','roles'));
    }

    public function subAdminEdit(User $user)
    {
        abort_if(Gate::denies('sub_admin_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if (auth()->user()->hasRole('Employee') && auth()->id() !== $user->id) {
            abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
        }
        return view('admin.users.sub-admin-edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //public function update(Request $request, $id)
    public function update(Request $request,$id)
    {
        $user = User::findOrFail($id);
        if (auth()->user()->hasRole('Employee') && auth()->id() !== $user->id) {
            abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => auth()->user()->hasRole('Admin') ? 'required|email|unique:users,email,' . $user->id : 'nullable',
            'phone' => 'required|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => auth()->user()->hasRole('Admin') ? 'required|exists:roles,id' : 'nullable',
        ]);
        $user->name = $request->name;
        $user->phone = $request->phone;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        if (auth()->user()->hasRole('Admin')) {
            $user->email = $request->email;
            $user->syncRoles([$request->role]);
        }
        if ($user->save()) {
            $documentTypes = DocumentType::all();
            foreach ($documentTypes as $documentType) {
                $fieldName = str_replace(' ', '_',$documentType->name);

                if ($request->hasFile($fieldName)) {
                    $existingDocument = $user->candidate->documents()
                        ->where('document_type_id', $documentType->id)
                        ->first();

                    if ($existingDocument) {
                        Storage::disk('s3')->delete($existingDocument->document_path);
                        $existingDocument->delete();
                    }

                    $file = $request->file($fieldName);
                    $fileName = $user->name."_".$file->getClientOriginalName();
                    $path = $file->storeAs("documents/{$user->candidate->id}",$fileName, 's3');

                    $user->candidate->documents()->create([
                        'document_type_id' => $documentType->id,
                        'document_path' => $path,
                    ]);
                }
            }

            if ($request->has('answers') && !empty($request->answers)) {
                foreach ($request->answers as $questionId => $answer) {
                    if (!empty($answer)) {
                        UserQuestionAnswer::updateOrCreate(
                            [
                                'user_id' => $user->id,
                                'question_id' => $questionId,
                            ],
                            [
                                'answer' => $answer,
                            ]
                        );
                    }
                }
            }
            toast( 'User updated successfully.','success');
            return redirect()->route('admin.users.index');
        }
        toast('User update failed.','error');
        return redirect()->route('admin.users.index');
    }

    public function subAdminUpdate(Request $request,$id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => auth()->user()->hasRole('Admin') ? 'required|email|unique:users,email,' . $user->id : 'nullable',
            'phone' => 'required|string|max:20',
            'password' => 'nullable|string|min:8|confirmed'
        ]);
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        if ($user->save()) {
            toast( 'User updated successfully.','success');
            return redirect()->route('admin.users.subAdmin.index');
        }
        toast('User update failed.','error');
        return redirect()->route('admin.users.subAdmin.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function banUnban($id, $status)
    {
        if (auth()->user()->hasRole('Admin')){
            $user = User::findOrFail($id);
            $user->status = $status;
            if ($user->save()){
                return redirect()->back()->with('message', 'User status updated successfully!');
            }
            return redirect()->back()->with('error', 'User status update fail!');
        }
        return redirect(Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    public function reviewForm($id, $status)
    {
        if (auth()->user()->hasRole('Admin')){
            $user = User::findOrFail($id);
            $policy = PolicyLead::where(['candidate_id'=> $user->candidate_id])->where('status',1)->first();
            if(empty($policy)){
                return redirect()->back()->with('error', 'User did not submitted the response of Company\'s Policy Questionnaires.');
            }
            $user->review_status = $status;
            if ($user->save()){
                return redirect()->back()->with('message', 'Review status updated successfully!');
            }
            return redirect()->back()->with('error', 'Review status update fail!');
        }
        return redirect(Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    public function sendQuestions($id)
    {
        if (auth()->user()->hasRole('Admin')){
            $user = User::find($id);
            $policy = PolicyLead::where(['candidate_id'=> $user->candidate_id])->first();
            if(!empty($policy)){
                PolicyLead::where('candidate_id', $user->candidate_id)
                ->update(['status' => 0]); 
                User::where(['id' => $id])->update(['review_status' => 0]);
            }
            $token = Crypt::encryptString($user->candidate_id);
            $user->notify(new CandidatePolicyLinkNotification($user,$token));
            toast('Mail sent successfully.','success');
            return redirect()->back();
        }
        return redirect(Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    public function viewQuestionsResponse(User $user){
        $policy = PolicyLead::where(['candidate_id'=> $user->candidate_id])->where(['status' => 1])->first();
        if(empty($policy))
            return redirect()->back()->with('error', 'Not available');

        return view('admin.users.questions-view', compact('policy'));

    }

      /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function subAdminDestroy(User $user)
    {
        abort_if(Gate::denies('sub_admin_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $user->delete();
        toast('Sub-Admin deleted successfully.','success');
        return redirect()->route('admin.users.subAdmin.index');
    }
}
