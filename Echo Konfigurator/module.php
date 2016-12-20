<?

	class AmazonEchoKonfigurator extends IPSModule
	{
		
		public function Create()
		{
			//Never delete this line!
			parent::Create();
			
			$this->RegisterPropertyInteger("countinstance", 0);
			for ($i=1; $i<=400; $i++)
			{
				$this->RegisterPropertyInteger("objectidinstance".$i, 0);
			}
			for ($i=1; $i<=400; $i++)
			{
				$this->RegisterPropertyString("alexaname".$i, "");
			}
			for ($i=1; $i<=400; $i++)
			{
				$this->RegisterPropertyInteger("vendor".$i, 0);
			}
			for ($i=1; $i<=400; $i++)
			{
				$this->RegisterPropertyInteger("type".$i, 0);
			}
		}
	
		public function ApplyChanges()
		{
			//Never delete this line!
			parent::ApplyChanges();
			
			
			$configid = $this->RegisterVariableString("AlexaConfig", "Alexa Konfiguration");
			IPS_SetHidden($configid, true);
			
			$this->ValidateConfiguration();	
		}
		
		private function ValidateConfiguration()
		{
			$change = false;
			
			$countinstance = $this->ReadPropertyInteger("countinstance");
			if($countinstance == 0)
			{
				$errorid = 208;
				$this->SetStatus($errorid); //Amazon Echo Select min one instance
				//break;
			}
			if($countinstance > 400)
					$countinstance = 400;
			$vendorcheck = false;
			$alexanamecheck = false;
			$objectidinstancecheck = false;
			$typecheck = false;
			// Echo instances
			for ($i=1; $i<=$countinstance; $i++)
			{
				${"objectidinstance".$i} = $this->ReadPropertyInteger('objectidinstance'.$i);
				${"alexaname".$i} = $this->ReadPropertyString('alexaname'.$i);
				${"vendor".$i} = $this->ReadPropertyInteger('vendor'.$i);
				${"type".$i} = $this->ReadPropertyInteger('type'.$i);
				//Vendorcheck
				if(${"vendor".$i} === 0)
				{
					$errorid = 300+$i;
					$this->SetStatus($errorid); // Amazon Alexa : select a vendor , errorid 301 - 700
					break;
				}
				else
				{
					$vendorcheck = true;
				}	
				//check Alexa Name
				if (${"alexaname".$i} === "")
				{
					$errorid = 700+$i;
					$this->SetStatus($errorid); // Amazon Alexa :  missing value, enter value in field alexa name, errorid 701 - 1100
					break;
				}
				else
				{
					$alexanamecheck = true;
				}
				//check instance
				if (${"objectidinstance".$i} === 0)
				{
					$errorid = 1100+$i;
					$this->SetStatus($errorid); // Amazon Alexa : select a instance , errorid 1101 - 1500
					break;
				}
				else
				{
					$objectidinstancecheck = true;
				}
				//check Alexa type
				if (${"type".$i} === 0)
				{
					$errorid = 1500+$i;
					$this->SetStatus($errorid); // Amazon Alexa : select a type , errorid 1501 - 1900
					break;
				}
				else
				{
					$typecheck = true;
				}
			}

			if ($objectidinstancecheck == true && $alexanamecheck == true && $vendorcheck == true && $typecheck == true) // OK
			{
				$this->WriteConfig();
				$this->SetStatus(102);
			}
		}	
		
		protected function WriteConfig()
		{
			$countinstance = $this->ReadPropertyInteger("countinstance");
			$config = array();
			// Echo instances
			for ($i=1; $i<=$countinstance; $i++)
			{
				${"objectidinstance".$i} = $this->ReadPropertyInteger('objectidinstance'.$i);
				${"alexaname".$i} = $this->ReadPropertyString('alexaname'.$i);
				${"vendor".$i} = $this->ReadPropertyInteger('vendor'.$i);
				${"type".$i} = $this->ReadPropertyInteger('type'.$i);
				$config[${"objectidinstance".$i}] = array("alexaname" => ${"alexaname".$i}, "vendor" => ${"vendor".$i}, "type" => ${"type".$i});  
			}
			$configjson = json_encode($config);
			SetValue($this->GetIDForIdent("AlexaConfig"), $configjson) ;
		}
		
				
		//Configuration Form
		public function GetConfigurationForm()
		{
			$countinstance = $this->ReadPropertyInteger("countinstance");
			$formhead = $this->FormHead();
			$formstatus = $this->FormStatus();
			$formalexa = $this->FormAlexa($countinstance);
			
			$formelementsend = '{ "type": "Label", "label": "__________________________________________________________________________________________________" }';
			if($countinstance == 0)// keine Auswahl
			{
				return	'{ '.$formhead.'],'.$formstatus.' }';
			}
			
			elseif ($countinstance > 0) // Alexa Auswahl
			{
				$formactions = $this->FormActions($countinstance);
				return	'{ '.$formhead.','.$formalexa.$formelementsend.'],'.$formactions.','.$formstatus.' }';
			}
		}
		
		protected function FormAlexa($countinstance)
		{
			$form = '{ "type": "Label", "label": "Amazon Alexa Instances____________________________________________________________________________________________" },
			{ "type": "Label", "label": "number of instances for Amazon Alexa (max 400)" },'
			.$this->FormAlexaInstances($countinstance);
			return $form;
		}
		
		protected function FormAlexaInstances($countinstance)
		{
			if ($countinstance > 0)
			{
				if($countinstance > 400)
				$countinstance = 400;
				$form = "";
				for ($i=1; $i<=$countinstance; $i++)
				{
					$form .= '{ "type": "Label", "label": "Alexa Name for invocation" },';
					$form .= '{ "name": "alexaname'.$i.'", "type": "ValidationTextBox", "caption": "Alexa name '.$i.'" },';
					$form .= '{ "type": "SelectInstance", "name": "objectidinstance'.$i.'", "caption": "instance '.$i.'" },';
					$form .= '{ "type": "Select", "name": "type'.$i.'", "caption": "device type '.$i.'",
								"options": [
									{ "label": "Please select", "value": 0 },
									{ "label": "Switch", "value": 1 },
									{ "label": "Dimmer", "value": 2 },
									{ "label": "et cetera", "value": 3 } 
									]
								},';
					$form .= '{ "type": "Select", "name": "vendor'.$i.'", "caption": "vendor '.$i.'",
								"options": [
									{ "label": "Please select", "value": 0 },
									{ "label": "FS20", "value": 1 },
									{ "label": "Homematic", "value": 2 },
									{ "label": "et cetera", "value": 3 }
									]
								},';
									
				}
				
			}
			else
			{
				$form = "";
			}
			return $form;
		}
		
		protected function FormHead()
		{
			$form = '"elements":
			[
				{ "type": "Label", "label": "Configuration for Amazon Echo to IP-Symcon" },
				{ "type": "Label", "label": "choose number of instances used with Amazon Echo" },
				{ "type": "Label", "label": "number of instances used for Alexa" },
				{ "type": "NumberSpinner", "name": "countinstance", "caption": "number of instances" }
				';
			
			return $form;
		}
		
		protected function FormActions($countrequestvars)
		{			
			$form = '"actions": [
			{ "type": "Label", "label": "Write Configuration as JSON" },
			{ "type": "Button", "label": "Write Config", "onClick": "AmazonEcho_WriteConfig($id);" },
			{ "type": "Label", "label": "______________________________________________________________________________________________________" }]';
			return  $form;
		}
		
		protected function FormStatus()
		{
			$form = '"status":
            [
                {
                    "code": 101,
                    "icon": "inactive",
                    "caption": "creating instance."
                },
				{
                    "code": 102,
                    "icon": "active",
                    "caption": "configuration created."
                },
				'.$this->FormStatusErrorVendor().'
                {
                    "code": 104,
                    "icon": "inactive",
                    "caption": "interface closed."
                },
				'.$this->FormStatusErrorAlexaName().'
				{
                    "code": 201,
                    "icon": "inactive",
                    "caption": "select number of values in module."
                },
				'.$this->FormStatusErrorType().'
				{
                    "code": 206,
                    "icon": "error",
                    "caption": "field must not be empty."
                },
				'.$this->FormStatusErrorInstance().'
				{
                    "code": 208,
                    "icon": "error",
                    "caption": "Select min one instance."
                }
			
            ]';
			return $form;
		}

		protected function FormStatusErrorVendor() // errorid 301 - 700
		{
			$form = "";
			for ($i=1; $i<=400; $i++)
			{
				$errorid = 300+$i;
				$form .= '{
                    "code": '.$errorid.',
                    "icon": "error",
                    "caption": "Amazon Alexa: select a vendor for vendor '.$i.'."
                },'; 
			}
			return $form;
		}
		
		protected function FormStatusErrorAlexaName() // errorid 701 - 1100
		{
			$form = "";
			for ($i=1; $i<=400; $i++)
			{
				$errorid = 700+$i;
				$form .= '{
                    "code": '.$errorid.',
                    "icon": "error",
                    "caption": "Amazon Alexa: missing value, enter value in field alexa name '.$i.'"
                },'; 
			}
			return $form;
		}
		
		protected function FormStatusErrorType() // errorid 1101 - 1500
		{
			$form = "";
			for ($i=1; $i<=400; $i++)
			{
				$errorid = 1100+$i;
				$form .= '{
                    "code": '.$errorid.',
                    "icon": "error",
                    "caption": "Amazon Alexa: select a type for type '.$i.'."
                },'; 
			}
			return $form;
		}
		
		protected function FormStatusErrorInstance() // errorid 1501 - 1900
		{
			$form = "";
			for ($i=1; $i<=400; $i++)
			{
				$errorid = 1500+$i;
				$form .= '{
                    "code": '.$errorid.',
                    "icon": "error",
                    "caption": "Amazon Alexa: select an instance for instance '.$i.'."
                },'; 
			}
			return $form;
		}
		
	
		// IP-Symcon Connect auslesen
		protected function GetIPSConnect()
		{
			$InstanzenListe = IPS_GetInstanceListByModuleID("{9486D575-BE8C-4ED8-B5B5-20930E26DE6F}");
			foreach ($InstanzenListe as $InstanzID) {
				$ConnectControl = $InstanzID;
			} 
			$connectinfo = CC_GetUrl($ConnectControl);
			if ($connectinfo == false || $connectinfo == "")
				$connectinfo = 'https://<IP-Symcon Connect>.ipmagic.de';
			return $connectinfo;
		}
		
		
		
		//Profile
		protected function RegisterProfileInteger($Name, $Icon, $Prefix, $Suffix, $MinValue, $MaxValue, $StepSize, $Digits)
		{
			
			if(!IPS_VariableProfileExists($Name)) {
				IPS_CreateVariableProfile($Name, 1);
			} else {
				$profile = IPS_GetVariableProfile($Name);
				if($profile['ProfileType'] != 1)
				throw new Exception("Variable profile type does not match for profile ".$Name);
			}
			
			IPS_SetVariableProfileIcon($Name, $Icon);
			IPS_SetVariableProfileText($Name, $Prefix, $Suffix);
			IPS_SetVariableProfileDigits($Name, $Digits); //  Nachkommastellen
			IPS_SetVariableProfileValues($Name, $MinValue, $MaxValue, $StepSize); // string $ProfilName, float $Minimalwert, float $Maximalwert, float $Schrittweite
			
		}
		
		protected function RegisterProfileIntegerAss($Name, $Icon, $Prefix, $Suffix, $MinValue, $MaxValue, $Stepsize, $Digits, $Associations)
		{
			if ( sizeof($Associations) === 0 ){
				$MinValue = 0;
				$MaxValue = 0;
			} 
			/*
			else {
				//undefiened offset
				$MinValue = $Associations[0][0];
				$MaxValue = $Associations[sizeof($Associations)-1][0];
			}
			*/
			$this->RegisterProfileInteger($Name, $Icon, $Prefix, $Suffix, $MinValue, $MaxValue, $Stepsize, $Digits);
			
			//boolean IPS_SetVariableProfileAssociation ( string $ProfilName, float $Wert, string $Name, string $Icon, integer $Farbe )
			foreach($Associations as $Association) {
				IPS_SetVariableProfileAssociation($Name, $Association[0], $Association[1], $Association[2], $Association[3]);
			}
			
		}
	
	}

?>
