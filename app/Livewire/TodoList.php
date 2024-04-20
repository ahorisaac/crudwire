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
    }

    public function render()
    {
        return view('livewire.todo-list', [
            'todos' => Todo::latest()->where('name', 'like', "%{$this->search}%")->paginate(5),
        ]);
    }
}
