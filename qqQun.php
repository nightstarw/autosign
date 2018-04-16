<?php
/**
 *  qq 群 签到脚本
 */
$method = $argv[1];
$skey = $argv[2];
$uin = 'o0qq号';

if(isset($method) && $method != ""){
    return $method($uin, $skey);
}else{
    echo "No function to call.";
}  

function sign($uin, $skey)
{
    file_put_contents('./date.txt', date('Y-m-d H:i:s', time()));
    new Sign($uin, $skey);
}

class Sign
{
    const URL = 'http://qun.qq.com/cgi-bin/qiandao/sign/publish'; // 签到url 地址

    public function __construct($uin, $skey)
    {
        $this->un = preg_replace('/^o0*/', '', $uin);
        $this->cookie = sprintf('Cookie: uin=%s; skey=%s;', $uin, $skey);
        $this->g_tk = $this->getGTK($skey);
        $this->sign($this->getQunList());
    }

    public function getGTK($sKey)
    {
        $len = strlen($sKey);
        $hash = 5381;
        for ($i = 0; $i < $len; $i++) {
            $hash += ($hash << 5) + ord($sKey[$i]);
        }

        return $hash & 0x7fffffff;
    }

    public function getQunList()
    {
        $html = file_get_contents(
            sprintf('http://qun.qzone.qq.com/cgi-bin/get_group_list?uin=%s&g_tk=%s', $this->un, $this->g_tk),
            false,
            stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'header' => $this->cookie
                ]
            ])
        );

        preg_match('/(\{[\s\S]+\})/', $html, $qunList);
        if (count($qunList) == 0) {
            return NULL;
        }
        $qunList = json_decode($qunList[1]);
        if ($qunList == NULL || $qunList->code != 0) {
            return NULL;
        }

        return $qunList->data->group;
    }

    public function sign($groups)
    {
        if ($groups == NULL) return;

        $i = 1;
        foreach ($groups as $group) {
            $this->toSign($qun->groupid);
            printf("%d\t%s(%d)\t签到完成\r\n", $i++, $qun->groupname, $qun->groupid);
        }
    }

    public function toSign($qin)
    {
        file_get_contents(self::URL, false,
            stream_context_create(
                [
                    'http' => [
                        'method'  => 'POST',
                        'header'  => $this->cookie,
                        'content' => sprintf('bkn=%s&gallery_info={"category_id":9,"page":0,"pic_id":16}&template_id=2&gc=%s&client=2&lgt=41.696620&lat=123.431340&poi=碧桂园公园外&text=起床啊', $this->g_tk, $qin)
                    ]
                ]
            )
        );
    }
}
