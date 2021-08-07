<?php

return array (
  0 => 
  array (
    'name' => 'random',
    'title' => '验证码字库',
    'type' => 'string',
    'content' => 
    array (
    ),
    'value' => '',
    'rule' => '',
    'msg' => '',
    'tip' => '默认使用英文加数字的组合，你在此可以自定义验证码字库',
    'ok' => '',
    'extend' => '',
  ),
  1 => 
  array (
    'name' => 'is_gif',
    'title' => '是否动图',
    'type' => 'radio',
    'content' => 
    array (
      1 => '是',
      0 => '否',
    ),
    'value' => '0',
    'rule' => 'required',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
  2 => 
  array (
    'name' => 'gif_fps',
    'title' => '动图帧数',
    'type' => 'number',
    'content' => 
    array (
    ),
    'value' => '10',
    'rule' => 'required',
    'msg' => '',
    'tip' => 'GIF动图的帧数，默认为10帧',
    'ok' => '',
    'extend' => '',
  ),
);
