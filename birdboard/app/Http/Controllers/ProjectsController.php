<?php

namespace App\Http\Controllers;

use App\Project;

class ProjectsController extends Controller
{
    public function index()
    {
		$projects = auth()->user()->projects;

		return view('projects.index', compact('projects'));
    }

    public function show(Project $project) // auto-inject
    {
        $this->authorize('update', $project);

    	return view('projects.show', compact('project'));
    }

    public function update(Project $project) // auto-inject
    {

        $this->authorize('update', $project);

        $attributes = $this->validateForm();

        $project->update($attributes);

        return redirect($project->path());
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store()
    {

        $attributes = $this->validateForm();

        $project = auth()->user()->projects()->create($attributes);

		return redirect($project->path());
    }

    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    protected function validateForm()
    {
        return request()->validate([
                'title' => 'sometimes|required', 
                'description' => 'sometimes|required',
                'notes' => 'min:3'
            ]);
    }
}
