<?php

/**
 * Created by shmilyelva
 * Date: 2019/3/26
 * Time: 下午4:28
 */
if (!function_exists('p')) {
    // 传递数据以易于阅读的样式格式化后输出
    function p($data)
    {
        $array = [];
        // 定义样式
        echo '<pre style="display: block;padding: 9.5px;margin: 44px 0 0 0;font-size: 13px;line-height: 1.42857;color: #333;word-break: break-all;word-wrap: break-word;background-color: #F5F5F5;border: 1px solid #CCC;border-radius: 4px;">';
        foreach($data as $key=>$value) {
            $array[$key] = json_decode(json_encode($value), true);
        }
        print_r($array);
        echo '</pre>';

    }
}

if (!function_exists('pd')) {
    // 传递数据以易于阅读的样式格式化后输出并终止
    function pd($data)
    {
        p($data);
        die;
    }
}
