<?php
namespace app\modules\workplan\widgets\form;

use app\common\helpers\ArrayHelper;
use app\common\helpers\Html;
use app\common\widgets\DatePicker;
use app\common\widgets\FormWidget;
use app\common\wrappers\DynamicModal;
use app\modules\organization\models\orm\Cabinet;
use app\modules\organization\models\orm\Department;
use app\modules\organization\models\orm\Employee;
use app\modules\workplan\application\WorkplanServiceInterface;
use app\modules\workplan\models\form\Workplan as WorkplanForm;
use app\modules\workplan\models\orm\Workplan;

/**
 * Class WorkplanCreateForm
 * @package Module\Workplan
 * @copyright 2012-2019 Medkey
 */
class WorkplanCreateForm extends FormWidget
{
    /**
     * @var WorkplanForm
     */
    public $model;
    /**
     * @var string
     */
    public $employeeId;
    /**
     * @var array
     */
    public $action = ['/workplan/rest/workplan/create'];
    /**
     * @var array
     */
    public $validationUrl = ['/workplan/rest/workplan/validate-create'];
    /**
     * @var WorkplanServiceInterface
     */
    public $workplanService;


    /**
     * WorkplanCreateForm constructor.
     * @param WorkplanServiceInterface $workplanService
     * @param array $config
     */
    public function __construct(WorkplanServiceInterface $workplanService, array $config = [])
    {
        $this->workplanService = $workplanService;
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->model = $this->workplanService->getWorkplanForm($this->model);
        $this->model->status = Workplan::STATUS_ACTIVE;
        $this->model->employee_id = $this->employeeId;
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function renderForm($model, $form)
    {
        echo Html::beginTag('div', [
            'class' => 'row',
        ]);
        echo Html::beginTag('div', [
            'class' => 'col-xs-12 col-sm-12 col-md-12 col-lg-12'
        ]);
        echo Html::beginTag('div', [
            'class' => 'row',
        ]);
        echo Html::beginTag('div', [
            'class' => 'col-xs-6 col-sm-6 col-md-6 col-lg-6'
        ]);
        echo $form->field($model, 'since_date')->dateInput([
            'type' => DatePicker::TYPE_INLINE,
        ]);
        echo Html::endTag('div'); // col-xs-6 ...
        echo Html::beginTag('div', [
            'class' => 'col-xs-6 col-sm-6 col-md-6 col-lg-6'
        ]);
        echo $form->field($model, 'expire_date')->dateInput([
            'type' => DatePicker::TYPE_INLINE,
        ]);
        echo Html::endTag('div'); // col-xs-6 ...
        echo Html::endTag('div'); // row
        echo Html::beginTag('div', [
            'class' => 'row',
        ]);
        echo Html::beginTag('div', [
            'class' => 'col-xs-6 col-sm-6 col-md-6 col-lg-6'
        ]);
        echo $form->field($model, 'since_time')->timeInput();
        echo Html::endTag('div');
        echo Html::beginTag('div', [
            'class' => 'col-xs-6 col-sm-6 col-md-6 col-lg-6'
        ]);
        echo $form->field($model, 'expire_time')->timeInput();
        echo Html::endTag('div'); // col-xs-6 col-sm-6 ...
        echo Html::endTag('div'); // row
        echo Html::beginTag('div', [
            'class' => 'row',
        ]);
        echo Html::beginTag('div', [
            'class' => 'col-xs-6 col-sm-6 col-md-6 col-lg-6',
        ]);
        echo $form->field($model, 'department_id')->dropDownList(Department::listAll(), [
            'empty' => 'Не выбрано',
        ]);
        echo Html::endTag('div');
        echo Html::beginTag('div', [
            'class' => 'col-xs-6 col-sm-6 col-md-6 col-lg-6'
        ]);
        echo $form->field($model, 'cabinet_id')->dropDownList(Cabinet::listAll(null, 'number', 'number'), [
            'empty' => false,
        ]);
        // @todo выбирать кабинеты ТОЛЬКО из выбранного подразделения
        echo Html::endTag('div'); // col-xs-6 col-sm-6 col-md-6 col-lg-6
        echo Html::endTag('div'); // row
        echo Html::beginTag('div', [
            'class' => 'row',
        ]);
        echo Html::beginTag('div', [
            'class' => 'col-xs-6 col-sm-6 col-md-6 col-lg-6'
        ]);
        echo $form->field($model, 'weekIds')->select2(WorkplanForm::listWeek(), [
            'multiple' => true,
        ]);
        echo Html::endTag('div'); // col-xs-6 col-sm-6 col-md-6 col-lg-6
        echo Html::endTag('div'); // row
        echo $form->field($model, 'employee_id')
             ->select2(ArrayHelper::map(Employee::find()->notDeleted()->all(), 'id', function ($row) {
                 return empty($row) ?: $row->last_name . ' ' . $row->first_name . ' ' . $row->middle_name;
             }));
        echo $form->field($model, 'status')->hiddenInput();
        echo Html::endTag('div'); // col-xs-12 col-sm-12 col-md-12 col-lg-12
        echo Html::endTag('div'); // row
        echo Html::submitButton('Сохранить', [
            'class' => 'btn btn-primary',
            'icon' => 'plus',
        ]);
        echo '&nbsp';
        echo Html::button('Отмена', [
            'class' => 'btn btn-default',
            'data-dismiss' => 'modal'
        ]);
    }

    /**
     * @inheritdoc
     */
    public function wrapperOptions()
    {
        return [
            'header' => 'Добавление рабочего плана',
            'wrapperClass' => DynamicModal::class,
        ];
    }
}
