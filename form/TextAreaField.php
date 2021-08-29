<?php
/**
 * @author kv.kn <aknk.v@protonmail.ch>
 * @product StudentLife
 * @package uglydavy\phpmvc\form
 */

namespace uglydavy\phpmvc\form;

class TextAreaField extends BaseField
{

    public function renderInput(): string
    {
        return sprintf
        ('
            <textarea name="%s" class="form-control%s">%s</textarea>
            ',
        $this->attribute,
            $this->model->hasError($this->attribute) ? ' is-invalid' : '',
            $this->model->{$this->attribute}
        );
    }
}