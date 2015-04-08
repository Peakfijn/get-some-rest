<?php namespace Peakfijn\GetSomeRest\Tests\Stubs;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class Resource
{
    public function someResources()
    {
        return $this;
    }

    public function get()
    {
        return $this;
    }

    public function findOrFail($id)
    {
        if ($id == 'fails') {
            throw new ModelNotFoundException();
        }

        return $this;
    }
}
