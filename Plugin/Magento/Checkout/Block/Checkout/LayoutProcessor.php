<?php


namespace SuttonSilver\CustomCheckout\Plugin\Magento\Checkout\Block\Checkout;

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


    public function __construct(
        \Magento\Checkout\Block\Checkout\AttributeMerger $merger,
        \Magento\Directory\Model\ResourceModel\Country\Collection $countryCollection,
        \Magento\Directory\Model\ResourceModel\Region\Collection $regionCollection
    ) {
        $this->merger = $merger;
        $this->countryCollection = $countryCollection;
        $this->regionCollection = $regionCollection;
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
                'value' => null,
            ],
            'region_id' => [
                'visible' => true,
                'formElement' => 'select',
                'label' => __('State/Province'),
                'options' => $this->regionCollection->load()->toOptionArray(),
                'value' => null,
            ]
        ];

        if (isset($jsLayout['components']['checkout']['children']['steps']['children']
            ['my-new-step']['children']['custom-checkout-form-home-address'])
        ) {

            $fieldSetPointer = &$jsLayout['components']['checkout']['children']['steps']['children']
            ['my-new-step']['children']['custom-checkout-form-home-address']['children'];
            $fieldSetPointer = $this->merger->merge($elements, 'checkoutProvider', 'personalDetails', $fieldSetPointer);
            $fieldSetPointer['region_id']['config']['skipValidation'] = true;
        }


        unset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['company']);
        return $jsLayout;
    }
}
