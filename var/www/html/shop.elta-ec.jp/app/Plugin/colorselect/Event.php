<?php

namespace Plugin\colorselect;


use Eccube\Entity\Product;
use Eccube\Event\TemplateEvent;
use Plugin\colorselect\Repository\ColorselectConfigRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Eccube\Repository\ProductClassRepository;
use Eccube\Repository\ProductRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Event implements EventSubscriberInterface
{


    /**
     * @var ColorselectConfigRepository
     */
    protected $ColorselectConfigRepository;


    /**
     * @var ProductClassRepository
     */
    protected $ProductClassRepository;
    /**
     * @var ProductRepository
     */
    protected $ProductRepository;

    /**
     * ProductReview constructor.
     *
     * @param ColorselectConfigRepository $ColorselectConfigRepository
     */
    public function __construct(
        ColorselectConfigRepository $ColorselectConfigRepository,
        ProductClassRepository $ProductClassRepository,
        ProductRepository $ProductRepository
    ) {
        $this->ColorselectConfigRepository = $ColorselectConfigRepository;
        $this->ProductClassRepository = $ProductClassRepository;
        $this->ProductRepository = $ProductRepository;

    }


    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'Product/detail.twig' => 'ColorSelectSnipper',
            '@admin/Product/product.twig' => 'EditSnipper'
        ];
    }
    /**
     * @param TemplateEvent $event
     */
    public function EditSnipper(TemplateEvent $event)
    {
        $event->addSnippet('@colorselect/admin/snipet.twig');
    }

    /**
     * @param TemplateEvent $event
     */
    public function ColorSelectSnipper(TemplateEvent $event)
    {
        $event->addSnippet('@colorselect/default/select.twig');
        $Config = $this->ColorselectConfigRepository->get();
        $stockmin = $Config->getStockMin();
        $Product =  (array) $event->getParameter('Product');
        $data = array();
        $Kdata = array(
          "Productstocks" =>"stock",
          "ProductstockUnlimiteds" =>"unlimit",
          "ProductclassCategories1" =>"cat1",
          "ProductclassCategories2" =>"cat2",
          "PRODUCTCLASSNAME1" =>"cat1name",
          "PRODUCTCLASSNAME2" =>"cat2name",
            "Productcodes" =>"pcode"

        );
        foreach ($Product as $key =>$value){
            $keys = (explode("\\",$key));
            $k = (string) $keys[2];
            $k = strtoupper(preg_replace("/[^a-z0-9]/i","",mb_convert_encoding($k,"UTF-8")));
            foreach ($Kdata as $eq => $dk){
                $eq = strtoupper(preg_replace("/[^a-z0-9]/i","",mb_convert_encoding($eq,"UTF-8")));
                if($eq == $k){
                    $data[$dk] = $value;
                }
            }
        }
        $c1stockdata = array();

        $stockdata = array();
        $i =0;

        if($data['cat2name'] != null){
            $data['type']="2";
            foreach ($data['cat2']  as $cat1 => $values){
                $cat1 = preg_replace("/".preg_quote("(品切れ中)")."/",'',$cat1);
                $stockdata[$data['cat1'][$cat1]] = array();
                foreach ($values as $value){
                    $value = preg_replace("/".preg_quote("(品切れ中)")."/",'',$value);
                    if($data['unlimit'][$i]){
                        $stock ="○";
                    } elseif($data['stock'][$i] > $stockmin ){
                        $stock ="○";
                    } elseif($data['stock'][$i] <= $stockmin &&   $data['stock'][$i]  > 0){
                        $stock ="△";
                    }else{
                        $stock ="×";
                    }
                    $stockdata[$data['cat1'][$cat1]][$value] =$stock;
                    $i++;
                }
            }

            foreach ($stockdata as $cat1 =>$cat2s){
                $flag = false;
                foreach ($cat2s as $cat2 => $val) {
                  if($val != "×" ){
                      $flag = true;
                  }
                }
                $c1stockdata[$cat1] = $flag;
            }

        }
        elseif($data['cat1name'] != null) {
            $data['type']="1";
            foreach ($data['cat1'] as $value){
                $value = preg_replace("/".preg_quote("(品切れ中)")."/",'',$value);
                if($data['unlimit'][$i]){
                    $stock ="○";
                } elseif($data['stock'][$i] > $stockmin ){
                    $stock ="○";
                } elseif($data['stock'][$i] <= $stockmin &&   $data['stock'][$i]  > 0){
                    $stock ="△";
                }else{
                    $stock ="×";
                }
                $stockdata[$value] =$stock;
                $i++;
            }
        }
        else{
            $data['type']="0";
                if($data['unlimit'][$i]){
                    $stock ="○";
                } elseif($data['stock'][$i] > $stockmin ){
                    $stock ="○";
                } elseif($data['stock'][$i] <= $stockmin &&   $data['stock'][$i]  > 0){
                    $stock ="△";
                }else{
                    $stock ="×";
                }
                $stockdata =$stock;
        }
        $stockdata2 = array();
        foreach ($data['pcode']  as $i => $code){
                if($data['unlimit'][$i]){
                    $stock ="○";
                } elseif($data['stock'][$i] > $stockmin ){
                    $stock ="○";
                } elseif($data['stock'][$i] <= $stockmin &&   $data['stock'][$i]  > 0){
                    $stock ="△";
                }else{
                    $stock ="×";
                }
                if($code == ""){
                    $stockdata2['0'] =$stock;
                }else{
                    $stockdata2[$code] =$stock;
                }
        }





        $parameters = $event->getParameters();

        $parameters['stockdata2'] =$stockdata2;
        $parameters['sdata'] =$data;
        $parameters['c1stockdata'] =$c1stockdata;



        $event->setParameters($parameters);


    }

    };