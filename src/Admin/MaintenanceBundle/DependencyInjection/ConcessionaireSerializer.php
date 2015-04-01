<?php

namespace Admin\MaintenanceBundle\DependencyInjection;
use JsonSerializable;

class ConcessionaireSerializer implements JsonSerializable
{
    private $items;
	private $result;

    public function __construct($items)
    {
        $this->items= $items;
		return $this->jsonSerialize();
    }

    public function getitems()
    {
        return $this->items;
    }

    /*public function jsonSerialize()
    {
        return [
            'concessionaire' => [
                'description' => $this->description
            ]
        ];
    }*/
    public function jsonSerialize() {
    	$this->result = array();
		foreach( $this->items as $item) {
			array_push($this->result, array(
					'description' => $item->getDescription(),
					'id' => $item->getIdconcessionaire()
				)
			);
		}
		return $this->result;
    }
}