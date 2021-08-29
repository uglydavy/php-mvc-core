<?php
/**
 * @author kv.kn <aknk.v@protonmail.ch>
 * @product StudentLife
 * @package uglydavy\phpmvc\form
 */

namespace uglydavy\phpmvc\form;

use uglydavy\phpmvc\Model;

/**
 * @property Model model
 * @property string attribute
 */

abstract class BaseField
{
    abstract public function renderInput(): string;

    public function __construct (Model $model, string $attribute)
    {
        $this->model = $model;
        $this->attribute = $attribute;
    }

    public function __toString ()
    {
        return sprintf
        ('
            <div class="col">
                <div class="mb-3">
                    <label>%s</label>
                    %s
                    <div class="invalid-feedback">
                        %s
                    </div>
                </div>
            </div>
        ',
            $this->model->getLabel($this->attribute),
            $this->renderInput(),
            $this->model->getFirstError($this->attribute)
        );
    }
}