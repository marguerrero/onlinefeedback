<?php

namespace Admin\LogsBundle\DependencyInjection;
use JsonSerializable;

class SysUserActionLogsSerializer implements JsonSerializable
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
	
    public function jsonSerialize() {
    	$this->result = array();
		foreach( $this->items as $item) {
			array_push($this->result, array(
					'module' => $item->getModule(),
					'affected_id' => $item->getAffectedId(),
					'affected_data' => $item->getAffectedData(),
					'username' => $item->getUsername(),
					'actionstamp' => $item->getActionstamp()->format('Y-m-d H:i:s')
				)
			);
		}
		return $this->result;
    }
}