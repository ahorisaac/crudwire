<?php

namespace App\Livewire;

use App\Models\Todo;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class TodoList extends Component
{
    use WithPagination;

    #[Rule('required|min:3|max:50')]
    public $name;

    public $search;

    public $editingTodoID;

    #[Rule('required|min:3|max:50')]
    public $editingTodoName;

    public function create()
    {
        // validate the name of the todo
        $validated = $this->validateOnly('name');

        // create the todo
        Todo::create($validated);

        // clear the input box
        $this->reset('name');

        // send a flash message
        session()->flash('success', 'Created.');

        $this->resetPage();
    }

    public function delete(Todo $todo)
    {
        $todo->delete();
    }

    public function toggle(Todo $todo)
    {
        $todo->completed = !$todo->completed;
        $todo->save();
    }

    public function edit(Todo $todo)
    {
        $this->editingTodoID = $todo->id;
        $this->editingTodoName = $todo->name;
    }

    public function cancel()
    {
        $this->reset('editingTodoID', 'editingTodoName');
    }

    public function update()
    {
        $this->validateOnly('editingTodoName');
        Todo::find($this->editingTodoID)->update(["name" => $this->editingTodoName]);

        $this->cancel();
    }

    public function render()
    {
        return view('livewire.todo-list', [
            'todos' => Todo::latest()->where('name', 'like', "%{$this->search}%")->paginate(5),
        ]);
    }
}
