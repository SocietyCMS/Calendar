<?php

namespace Modules\Calendar\Http\Controllers\api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Calendar\Repositories\PresetRepository;
use Modules\Calendar\Transformers\PresetTransformer;
use Modules\Core\Http\Controllers\ApiBaseController;

/**
 * Class PresetController
 * @package Modules\Calendar\Http\Controllers\api
 */
class PresetController extends ApiBaseController
{

    /**
     * @var PresetRepository
     */
    private $preset;

    /**
     * EventController constructor.
     * @param PresetRepository $event
     */
    public function __construct(PresetRepository $preset)
    {
        parent::__construct();
        $this->preset = $preset;
    }

    /**
     * @return array
     */
    public function index(Request $request)
    {
        $presets = $this->preset->all();

        return $this->response->collection($presets, new PresetTransformer);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        $input = array_merge($request->data, [
            'end' => (is_null($request->data['end']) && $request->data['allDay'] == true) ? Carbon::parse($request->data['start'])->addDay() : $request->data['end']
        ]);

        $preset = $this->preset->update($input, $id);
        return $this->response->item($preset, new PresetTransformer);
    }


    /**
     * @param EventRequest $request
     * @return mixed
     */
    public function store(PresetRepository $request)
    {
        $preset = $this->preset->create($request->input());

        return $this->successCreated();
    }
}
