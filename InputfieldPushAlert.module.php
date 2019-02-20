<?php namespace ProcessWire;

class InputfieldPushAlert extends Inputfield  implements InputfieldHasArrayValue {

    const PA = 'pushalert';

     protected $page;
    protected $field;

    public function setPage(Page $page) {
        $this->page = $page;
    }

    public function setField(Field $field) {
        $this->field = $field;
    }


    /**
     * Construct
     *
     * @throws WireException
     *
     */
    public function __construct() {
        parent::__construct();
        $this->setAttribute('type', self::PA);
        $this->setAttribute('size', 0);
        $this->set('requiredAttr', 0);

        // if multi-language, support placeholders for each language
        $languages = $this->wire('languages');
        if($languages) foreach($languages as $language) {
            // set to blank value so that Field::getInputfield() will recogize this setting is for InputfieldText
            if(!$language->isDefault()) $this->set("placeholder$language", '');
        }
    }

    /**
     * Set Inputfield setting
     *
     * @param string $key
     * @param mixed $value
     * @return Inputfield|InputfieldPushAlert
     *
     */
    public function set($key, $value) {
         return parent::set($key, $value);
    }



    /**
     * Process input for the PushAlert inputs
     *
     */
    public function ___processInput(WireInputData $input) {

        if(!$this->page || !$this->field) {
            throw new WireException("This inputfield requires that you set valid 'page' and 'field' properties to it.");
        }
        parent::___processInput($input);
        return $this;

    }

    /**
     * Render the entire input area for Events
     *
     */
    public function ___render() {
        $out = $this->getMessageFields()->render();
        return $out;
    }

    public function ___getMessageFields () {

        $pushAlertMod = $this->wire('modules')->get('PushAlert');
        $vals = (object) $pushAlertMod->getFieldData ($this->page, $this->field);

        $wrapper = $this->wire(new InputfieldWrapper());

        // Message title input field
        $inputfield = $this->wire(new InputfieldText);
        $inputfield->set('name', 'pa_message_title');
        $inputfield->set('label', _('Message title'));
        $inputfield->set('striptags',1);
        $inputfield->set('maxlength', 64);
        $inputfield->set('showCount', InputfieldText::showCountChars);
        if (!empty($vals) && isset($vals->pa_message_title)) {
            $inputfield->set('value', $vals->pa_message_title);
        }
        $wrapper->append($inputfield);

        // Message body input field
        $inputfield = $this->wire(new InputfieldTextarea);
        $inputfield->set('name', 'pa_message_body');
        $inputfield->set('label', _('Message body'));
        $inputfield->set('maxlength', 192);
        $inputfield->set('showCount', InputfieldTextarea::showCountChars);
        if (!empty($vals) && isset($vals->pa_message_body)) {
            $inputfield->set('value', $vals->pa_message_body);
        }
        $wrapper->append($inputfield);

        // Message urlinput field
        $inputfield = $this->wire(new InputfieldURL);
        $inputfield->set('name', 'pa_message_url');
        $inputfield->set('label', _('Message URL'));
        $inputfield->set('noRelative', true);
        if (!empty($vals) && isset($vals->pa_message_url)) {
            $inputfield->set('value', $vals->pa_message_url);
        }
        $wrapper->append($inputfield);

        // Send on publish checkbox field
        $inputfield = $this->wire(new InputfieldCheckbox());
        $inputfield->set('name', 'pa_send_on_publish');
        $inputfield->set('label', _('Send on publish'));
        if (!empty($vals) && isset($vals->pa_send_on_publish)) {
            if ($vals->pa_send_on_publish == "1") {
                $inputfield->set('value', (int) $vals->pa_send_on_publish);
                $inputfield->attr('checked', 'checked');
            }
        }
        $wrapper->append($inputfield);

        // Hidden message id field
        $inputfield = $this->wire(new InputfieldHidden());
        $inputfield->set('name', 'pa_message_id');
        $inputfield->set('label', _('Message ID'));
        if (!empty($vals) && isset($vals->pa_message_id)) {
            $inputfield->set('value', $vals->pa_message_id);
        }
        $wrapper->append($inputfield);

        // Markup field for results
        $inputfield = $this->wire(new InputfieldMarkup());
        $inputfield->set('name', 'pa_message_results');
        $inputfield->set('label', _('Sent Notification Results'));
        if (!empty($vals) && isset($vals->pa_message_id) && !empty($vals->pa_result_json)) {

            $messageId = $vals->pa_message_id;

            $out = '';
            $out .= "<h3>" . _("Message Id: ") . $messageId ."</h3>";
            $out .= "<p>"  . _("Attempted: ") . $vals->pa_result_json->attempted . "</p>";
            $out .= "<p>"  . _("Delivered: ") . $vals->pa_result_json->delivered . "</p>";
            $out .= "<p>"  . _("Clicked: ") . $vals->pa_result_json->clicked . "</p>";
            $out .= "<p>"  . _("CTR: ") . $vals->pa_result_json->ctr . "</p>";


            $inputfield->set('value', $out);
        } else {
            $inputfield->set('value', _("No results yet"));
        }
        $wrapper->append($inputfield);

        return $wrapper;
    }

    public function ___getConfigInputfields() {
        $inputfields = parent::___getConfigInputfields();
        $inputfields->append($this->getMessageFields());
    return $inputfields;
    }
}

