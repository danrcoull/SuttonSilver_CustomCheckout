<?php


namespace SuttonSilver\CustomCheckout\Plugin\Magento\Checkout\Block\Checkout;
use SuttonSilver\CustomCheckout\Model\ResourceModel\Question\CollectionFactory as QuestionCollectionFactory;
use SuttonSilver\CustomCheckout\Model\ResourceModel\QuestionValues\CollectionFactory as QuestionValuesCollectionFactory;
use Magento\Checkout\Model\Session as Session;
class LayoutProcessor
{
    /**
     * @var \Magento\Checkout\Block\Checkout\AttributeMerger
     */
    protected $merger;

    /**
     * @var \Magento\Directory\Model\ResourceModel\Country\Collection
     */
    protected $countryCollection;

    /**
     * @var \Magento\Directory\Model\ResourceModel\Region\Collection
     */
    protected $regionCollection;

    protected $delivery;

    protected $questionsCollection;
    protected $questionsValuesCollection;
    protected $session;


    public function __construct(
        \Magento\Checkout\Block\Checkout\AttributeMerger $merger,
        \Magento\Directory\Model\ResourceModel\Country\Collection $countryCollection,
        \Magento\Directory\Model\ResourceModel\Region\Collection $regionCollection,
        \SuttonSilver\CustomCheckout\Model\Attribute\Source\Delivery $delivery,
        QuestionCollectionFactory $questionsCollection,
        QuestionValuesCollectionFactory $questionsValuesCollection,
        Session $session
    ) {
        $this->merger = $merger;
        $this->countryCollection = $countryCollection;
        $this->regionCollection = $regionCollection;
        $this->delivery = $delivery;
        $this->questionsCollection = $questionsCollection;
        $this->questionsValuesCollection = $questionsValuesCollection;
        $this->session = $session;
    }

    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        array  $jsLayout
    ) {

        $elements = [
            'country_id' => [
                'visible' => true,
                'formElement' => 'select',
                'label' => __('Country'),
                'options' => $this->countryCollection->loadByStore()->toOptionArray(),
                'value' => null
            ],
            'region_id' => [
                'visible' => true,
                'formElement' => 'select',
                'label' => __('County'),
                'options' => $this->regionCollection->load()->toOptionArray(),
                'value' => null,
            ]
        ];

        if (isset($jsLayout['components']['checkout']['children']['steps']['children']
            ['my-new-step']['children']['custom-checkout-form-home-address'])
        ) {

            $fieldSetPointer = &$jsLayout['components']['checkout']['children']['steps']['children']
            ['my-new-step']['children']['custom-checkout-form-home-address']['children'];
            $fieldSetPointer = $this->merger->merge($elements, 'checkoutProvider', 'homeAddress', $fieldSetPointer);
            $fieldSetPointer['region_id']['config']['skipValidation'] = true;

        }


        unset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['shippingAddress']['children']['shipping-address-fieldset']['children']['firstname']);

        unset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['shippingAddress']['children']['shipping-address-fieldset']['children']['prefix']);

        unset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['shippingAddress']['children']['shipping-address-fieldset']['children']['lastname']);


        unset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['shippingAddress']['children']['shipping-address-fieldset']['children']['telephone']);

        unset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['shippingAddress']['children']['customer-email']);

       /** $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['postcode']['label'] = "Postcode";

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['street']['label'] = "";

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['street']['children'][0]['label'] = "First Line of Address";


        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['region_id']['label'] = "County"; **/

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['before-fields']['children']['select-shipping-address'] = [
            'component' => 'SuttonSilver_CustomCheckout/js/form/element/checkbox-set-shipping',
            'config' => [
                'customScope' => 'shippingAddress',
                'template' => 'ui/form/element/checkbox-set',
                'options' => $this->delivery->toOptionArray(),
                'id'=>'select_address',
                'multiple'=> false,
            ],
            'dataScope' => 'shippingAddress.select_address',
            'default' => 0,
            'label' => 'Deliver To',
            'provider' => 'checkoutProvider',
            'visible' => true,
            'validation' => ['required-entry' => true],
            'sortOrder' => 1,
        ];

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['before-fields']['children']['select-address-error'] = [
            'component' => 'Magento_Ui/js/form/components/html',
            'config' => [
                'customScope' => 'shippingAddress',
            ],
            'dataScope' => 'shippingAddress.select_address',
            'content' => '',
            'provider' => 'checkoutProvider',
            'sortOrder' => 2,
            'additionalClasses' => 'warning shiping-address-warning',
        ];

        $questions = $this->questionsCollection->create()
            ->addFieldToFilter('question_is_active',1)
            ->addFieldToFilter('product_skus', $this->getAllCartItems());

        $options = [];
        foreach($questions as $question)
        {
            $name = trim(str_replace(' ','-',$question->getQuestionName()));
            $label = __($question->getQuestion());
            $placeholder = trim($question->getQuestionPlaceholder());
            $required = ($question->getQuestionIsRequired()) ? true : false;

            $values = $this->questionsValuesCollection->create()
                ->addFieldToFilter('question_id',$question->getId());

            $valueArray = [];
            foreach($values as $value)
            {
                $valueArray[] = [
                    'value' => $value->getQuestionValue(),
                    'label' => __($value->getQuestionValue()),
                ];
            }

            switch($question->getQuestionType())
            {
                case 'text':
                    $options[$name] = [
                        'component' => 'Magento_Ui/js/form/element/abstract',
                        'config' => [
                            'customScope' => 'additionalDetails',
                            'template' => 'ui/form/field',
                            'elementTmpl' => 'ui/form/element/input',
                            'id'=>$name,
                        ],
                        'additionalClasses' => '',
                    ];
                    break;
                case 'textarea':
                    $options[$name] = [
                        'component' => 'Magento_Ui/js/form/element/textarea',
                        'config' => [
                            'customScope' => 'additionalDetails',
                            'template' => 'ui/form/field',
                            'elementTmpl' => 'ui/form/element/textarea',
                            'id'=>$name,
                        ],
                        'additionalClasses' => '',
                    ];
                    break;
                case 'select':
                    $options[$name] = [
                        'component' => 'Magento_Ui/js/form/element/select',
                        'config' => [
                            'template' => 'ui/form/field',
                            'customScope' => 'additionalDetails',
                            'elementTmpl' => 'ui/form/element/select',
                            'options' => $valueArray,
                            'id'=>$name
                        ],

                    ];
                    break;
                case 'checkbox':
                case 'radio':
                    $options[$name] = [
                        'component' => 'Magento_Ui/js/form/element/checkbox-set',
                        'config' => [
                            'customScope' => 'additionalDetails',
                            'template' => 'ui/form/element/checkbox-set',
                            'options' => $valueArray,
                            'id'=>$name,
                            'multiple'=> ($question->getQuestionType()=='radio') ? false : true,
                        ],

                    ];
                    break;

                case 'yes-no':
                        $options[$name] = [
                            'component' => 'Magento_Ui/js/form/element/single-checkbox',
                            'config' => [
                                'customScope' => 'additionalDetails',
                                'template' => 'ui/form/field',
                                'elementTmpl' => 'ui/form/components/single/switcher',
                                'id' => $name,
                            ],
                            'additionalClasses' => '',
                            'prefer' => 'toggle',
                            'valueMap' => ['true' => 1, 'false' => 0],
                        ];

                    break;

            }
            $options[$name]['placeholder'] = $placeholder;
            $options[$name]['label'] = $label;
            $options[$name]['dataScope'] = 'additionalDetails.'.$name;
            $options[$name]['sortOrder'] = $question->getQuestionPosition() ?: 1;
            $options[$name]['visible'] = true;
            $options[$name]['provider'] = 'checkoutProvider';
            if($description = $question->getQuestionTooltip() != '') {
                $options[$name]['tooltip']['description'] = $description;
            }

            if($required)
            {
                $options[$name]['validation'] = ['required-entry' => $required];
            }

        }

      if(isset($jsLayout['components']['checkout']['children']['steps']['children']['my-new-step'])) {
          $jsLayout['components']['checkout']['children']['steps']['children']
          ['my-new-step']['children']['custom-checkout-form-additional'] =[
              'component' => 'uiComponent',
              'displayArea' => 'additional',
              'children' => $options
          ];

        }

        //  var_dump($jsLayout['components']['checkout']['children']['steps']['children']
       // ['my-new-step']['children']['custom-checkout-form-home-address']['children']);
       // die;

        return $jsLayout;
    }

    /**
     * Get  \Magento\Checkout\Model\Session $cart all visible items.
     * @return array $ids
     */
    public function getAllCartItems()
    {

        $items = $this->session->getQuote()->getAllVisibleItems();
        $ids = [];
        foreach ($items as $item)
        {
            $ids[] =  array('finset'=> array($item->getProduct()->getId()));
        }
        return $ids;
    }
}
