<?php

namespace App\Http\Controllers;

use App\Project;
use App\User;
use App\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use function MongoDB\BSON\toJSON;
use PhpParser\Node\Expr\Array_;

class ProjectController extends Controller
{

    private $user;


    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $projects = $user->projects;
        $createdProjects = $user->createdProjects;
        return view('project.index')->with(compact('user', 'createdProjects', 'projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('project.create')->with(compact('project'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->session()->push('emails', $request->emails);
        $request->validate([
            'name' => 'required|unique:projects|max:100',
            'description' => 'max:255',
        ]);
        $project = new Project;
        $project->creator_id = Auth::user()->id;
        $project->name = $request->name;
        $project->description = $request->description;

        if ($project->save()) {
            $file = File::setUp($project->id);

            $emails = $request->emails;
            foreach ($emails as $email) {
                $user = User::where('email', $email)->first();
                if ($user) {
                    $user->projects()->save($project);
                    $url = url('/project');
                    $data = [
                        'title' => 'Bienvenue',
                        'content' => 'Parce que c\'est NOTRE PROJET ' . $url,
                    ];
                    /*Mail::send('mailwelcome', $data, function ($message) use ($email) {
                        $message->to($email)->subject('ça marche');
                    });*/
                } else {
                    if ($email != "") {
                        $url = url('/register');
                        $data = [
                            'title' => 'Bienvenue',
                            'content' => 'Inscrit toi ici : ' . $url,
                        ];
                        /*Mail::send('mailwelcome', $data, function ($message) use (&$email) {
                            $message->to($email)->subject('ça marche');
                        });*/
                    }
                }
            }
            if ($request->session()->has('emails')) {
                $request->session()->pull('emails');
            }
            return redirect(route('project.show', [$project, 'html']));
        }
        return redirect(route('project.create'))->withErrors('An error occured, please try again later');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Project $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project, $type)
    {
        $user = Auth::user();
        $userInfo = ['name' => $user->name, 'id' => $user->id, 'email' => $user->email];
        $userInfo = json_encode($userInfo);
        return view('project.show')->with(compact('project', 'userInfo', 'type'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Project $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        return view('project.edit')->with(compact('project'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Project $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        $request->session()->push('emails', $request->emails);
        $request->validate([
            'name' => 'required|max:100',
            'description' => 'max:255',
        ]);


        $project = Project::find($project->id);
        $project->name = $request->name;
        $project->description;


        if ($project->update()) {
            $emails = $request->emails;
            foreach ($emails as $email) {
                $user = User::where('email', $email)->first();
                if ($user && !in_array($email, $project->getUsersEmail())) {
                    $user->projects()->save($project);
                    $url = url('/project');
                    $data = [
                        'title' => 'Bienvenue',
                        'content' => 'Parce que c\'est NOTRE PROJET ' . $url,
                    ];
                   /* Mail::send('mailwelcome', $data, function ($message) use ($email) {
                        $message->to($email)->subject('ça marche');
                    });*/
                } else {
                    if ($email != "") {
                        $url = url('/register');
                        $data = [
                            'title' => 'Bienvenue',
                            'content' => 'Inscrit toi ici : ' . $url,
                        ];
                        /*Mail::send('mailwelcome', $data, function ($message) use (&$email) {
                            $message->to($email)->subject('ça marche');
                        });*/

                    }
                }
            }
            return redirect(route('project.show', [$project, 'html']));
        }
        return redirect(route('project.create'))->withErrors('An error occured, please try again later');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project $project
     * @return \Illuminate\Http\Response
     */
    public
    function destroy(Project $project)
    {
        $project->delete();
        return redirect(route('project.index'));
    }


    public
    function live(Project $project)
    {
        $files = $project->files;
        $html = $files->where('type', 'html')->first()->content;
        $css = $files->where('type', 'css')->first()->content;
        $js = $files->where('type', 'javascript')->first()->content;
        return view('live.live')->with(compact('project', 'html', 'css', 'js'));
    }


}


