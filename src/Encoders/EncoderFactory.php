<?php namespace Peakfijn\GetSomeRest\Encoders;

use Illuminate\Http\Request;

class EncoderFactory {

    function __construct()
    {
        $this->encoders = config('get-some-rest.encoders');
        $this->defaultEncoder = config('get-some-rest.defaultEncoder');
    }

    /**
     * Make a new encoder.
     *
     * @param Request $request
     * @return mixed
     * @throws UndefinedEncoderTypeException
     */
    public function make(Request $request)
    {
        $encoderType = ($request->get('format')) ?: $this->defaultEncoder;
        if ( ! array_key_exists($encoderType, $this->encoders)) {
            throw new UndefinedEncoderTypeException;
        }

        return new $this->encoders[$encoderType];
    }

}