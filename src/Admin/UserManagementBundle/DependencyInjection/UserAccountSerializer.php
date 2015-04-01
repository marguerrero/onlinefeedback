<?php

namespace Admin\UserManagementBundle\DependencyInjection;
use JsonSerializable;

class UserAccountSerializer implements JsonSerializable
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
					'username' => $item->getUsername(),
					'email' => $item->getEmail(),
					'user_role' => $item->getUserRole(),
					'active' => $item->getActive()
				)
			);
		}
		return $this->result;
    }
}