<?php namespace ProcessWire;

class PushAlertConfig extends ModuleConfig {

    public function getDefaultConfig()
    {
        return array(
            "api_key"          => "",
            "web_id"           => "",
            "api_url"          => "https://api.pushalert.co/rest/v1/",
        );
    }

    /**
     * @param array $data
     * @return InputfieldWrapper
     * @throws WirePermissionException
     */
    public function getInputfields()
    {
        $data = $this->getDefaultConfig();
        $inputfields = new InputfieldWrapper();

        $f = $this->modules->get('InputfieldText');
        $f->name = 'api_key';
        $f->label = __('PushAlert API key');
        $f->collapsed = 5 ; // collapsedPopulated
        $f->value = $data['api_key'];
        $inputfields->add($f);

        $f = $this->modules->get('InputfieldText');
        $f->name = 'web_id';
        $f->label = __('PushAlert Web Id');
        $f->collapsed = 5 ; // collapsedPopulated
        $f->value = $data['web_id'];
        $inputfields->add($f);

        $f = $this->modules->get('InputfieldText');
        $f->name = 'api_url';
        $f->label = __('PushAlert API Base URL');
        $f->collapsed = 5 ; // collapsedPopulated
        $f->value = $data['api_url'];
        $inputfields->add($f);

        return $inputfields;
    }
}