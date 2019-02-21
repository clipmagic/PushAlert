<?php namespace ProcessWire;

/**
 * A field that enables the sending and data reporting on push notifications from PushAlert from within ProcessWire
 *
 *
 */

class FieldtypePushAlert extends Fieldtype {

    const PA = 'pushalert';

    static public function getDefaultFields() {

        return array(
            'pa_message_title',
            'pa_message_body',
            'pa_message_url',
            'pa_icon',
            'pa_message_id',
            'pa_schedule',
            'pa_send_on_publish',
            'pa_message_id',
            'pa_result_json'
        );
    }

    /**
     * Initialize this Fieldtype
     *
     */
    public function init() {
        parent::init();
    }


    /**
     * Return the associated Inputfield
     *
     * @param Page $page
     * @param Field $field
     * @return InputfieldPassword
     *
     */
    public function getInputfield(Page $page, Field $field) {

        /** @var InputfieldPushAlert $inputfield */
        $inputfield = $this->modules->get('InputfieldPushAlert');
        $inputfield->class = $this->className();
        $inputfield->setPage($page);
        $inputfield->setField($field);
        return $inputfield;
    }


    /**
     * Return the database schema that defines an Event
     *
     */
    public function getDatabaseSchema(Field $field) {
        $schema = parent::getDatabaseSchema($field);
        $schema['data'] = 'text NOT NULL';
        $schema['keys']['data'] = 'FULLTEXT KEY `data` (`data`)';

        return $schema;
    }

    /**
     * Sanitize value for runtime
     *
     * @param Page $page
     * @param Field $field
     * @param Password|string $value
     * @return Password
     *
     */


    public function sanitizeValue(Page $page, Field $field, $value) {
 
        return $value;
    }

    /**
     * Given a raw value (value as stored in DB), return the value as it would appear in a Page object
     *
     * @param Page $page
     * @param Field $field
     * @param string|int|array $value
     * @return string|int|array|object $value
     *
     */
    public function ___wakeupValue(Page $page, Field $field, $value) {

        // if we were given a blank value, then we've got nothing to do: just return
        if(empty($value) ) return  ;
        // save the results in a session var to repopulate the subfields on render
        $session = $this->wire('session');
        $session->set('pushalertjson', $value);

        return ;

    }


    /**
     * Given an 'awake' value, as set by wakeupValue, convert the value back to a basic type for storage in DB.
     *
     * @param Page $page
     * @param Field $field
     * @param string|int|array|object $value
     * @return string|int
     *
     */
    public function ___sleepValue(Page $page, Field $field, $value)
    {
        $input = $this->wire('input');
        $sleepValue = '';

        if(!isset($input->post[$field->name])) {
            return $sleepValue;
        }
        $sleepValue = $input->post[$field->name];
        return $sleepValue;
    }


    public function ___getConfigInputfields(Field $field) {
        $inputfields = parent::___getConfigInputfields($field);
        return $inputfields;
    }

}

