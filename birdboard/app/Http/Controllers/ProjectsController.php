<?php

namespace App\Http\Controllers;

use App\Project;
use App\Http\Requests\UpdateProjectRequest;

class ProjectsController extends Controller
{
    public function index()
    {
		$projects = auth()->user()->accessibleProjects();

		return view('projects.index', compact('projects'));
    }

    public function show(Project $project) // auto-inject
    {
        $this->authorize('update', $project);

    	return view('projects.show', compact('project'));
    }

    public function update(UpdateProjectRequest $form, Project $project) // auto-inject
    {

        $project->update($form->validated());

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

        if ($tasks = request('tasks')) {

            $project->addTasks($tasks);

        }

		return redirect($project->path());
    }

    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    public function destroy(Project $project)
    {
        $this->authorize('manage', $project);
        
        $project->delete();

        return redirect('/projects');
    }

    protected function validateForm()
    {
        return request()->validate([
                'title' => 'sometimes|required', 
                'description' => 'sometimes|required',
                'notes' => 'nullable'
            ]);
    }
}
