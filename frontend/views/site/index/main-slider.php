<?php
/**
 * Created by PhpStorm.
 * User: hellios
 * Date: 10.03.16
 * Time: 11:57
 */
?>
<?=!empty($items) ? Slick::widget([
                                      'containerOptions' => [
                                          'id'    => 'sliderFor',
                                          'class' => 'first'
                                      ],
                                      'items' =>  '',
                                      'clientOptions' => [
                                          'arrows'         => false,
                                          'fade'           => true,
                                          'slidesToShow'   => 1,
                                          'slidesToScroll' => 1,
                                          'asNavFor'       => '#sliderNav',
                                      ]
                                  ]) : '<div style="height: 450px;
																	width: 450px;
																	border-left: 1px solid rgb(236, 236, 236);
																	box-shadow: 0px 1px 0px rgb(236, 236, 236);
																	border-bottom: 1px solid rgb(222, 222, 222);
																	border-right: 1px solid rgb(236, 236, 236);
																	background: white;
																	border-radius: 4px;
																	float: left;
																	margin-right: 10px;
																	margin-left: 10px;">
														</div>'
?>
