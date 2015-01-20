<?php
namespace common\components\nestsort;

use yii\base\Widget;
use yii\helpers\Url;

class NestableWidget extends Widget
{
    public $data = [];
    public $options = [];
    public $datacount = 0;

	public function init()
	{
		parent::init();
	}
    public function run()
    {
        $thistree = '<ol class="sortable">';
        $pusto = "";
        $i = 0;
        $this->datacount = count($this->data);
        $subtree = $this->buildtree($pusto,$i, 1);
        $thistree = $thistree.$subtree."</ol>";
        echo $thistree;
        $this->registerClientScript();
    }
    public function buildtree($thistree, $i, $depth)
    {
        $i = $i + 1;
        if($this->data[$i][depth] != $depth){
            $stage = $depth - $this->data[$i][depth];
            $x = 0;
            while($x < $stage)
            {
                $thistree = $thistree.'</ol></li>';
                $x++;
            }
        }

        if($i < $this->datacount)
        {
        $elem = '<div class="nestitem"><span class="glyphicon glyphicon-fullscreen"></span><strong>'.$this->data[$i][name].'</strong><p><a href="#" data-id="'.$this->data[$i][id].'" title="Просмотр">
<span class="glyphicon glyphicon-eye-open"></span></a>
<a href="/admin/products/update?id='.$this->data[$i][id].'" title="Редактировать">
<span class="glyphicon glyphicon-pencil">
</span>
</a>
<a href="/admin/products/delete?id='.$this->data[$i][id].'" title="Удалить" data-confirm="Вы уверены, что хотите удалить этот элемент?" data-method="post">
<span class="glyphicon glyphicon-trash">
</span>
</a></p>
</div>';

                    if($this->data[$i][lft] == ($this->data[$i][rgt] - 1))
                    {
                    $thistree = $thistree.'<li id="'.$this->data[$i][id].'">';
                    $thistree = $thistree.$elem;
                    $thistree = $thistree.'</li>';
                    $thistree = $this->buildtree($thistree, $i, $this->data[$i][depth]);
                    }else{
                    $thistree = $thistree.'<li id="'.$this->data[$i][id].'">';
                    $thistree = $thistree.$elem;
                    $thistree = $thistree.'<ol>';
                    $thistree = $this->buildtree($thistree, $i, $this->data[$i][depth]);
                    }
        }

        return $thistree;

    }

    public function registerClientScript()
    {
        $view = $this->getView();
        NestableAssets::register($view);
        $js[] = "var ns = $('ol.sortable').nestedSortable({";
        foreach($this->options as $key=>$oneopt)
        {
            $js[] = $key.": '".$oneopt."',";
        }
			$js[] = "relocate: function(event, data){
				var id = data.item.attr('id');
				var next = $('#' + id).next('li').attr('id');
				var parentli = $('#' + id).parents().parents().attr('id');
                $.ajax({
                      url: '".Url::to(['products/ajaxmenu'])."',
                      data: 'id=' + id + '&next=' + next + '&parentli=' + parentli,
                      success: function(){
                      $('#' + id + ' strong').append('<span></span>');
                      $('#' + id + ' strong span').addClass('glyphicon glyphicon-ok');
                      $('#' + id + ' strong span').fadeOut(3000,
					function(){
						$('div#okay').remove();
					}
				);
                      }
                    });
                }
                });";
        $js[] = "
        $('.mjs-nestedSortable-branch a').click(function() {
        var content = $(this).attr('data-id');
        $.ajax({
                      url: '".Url::to(['products/view'])."',
                      data: 'id=' + content,
                      success: function(data){
                      $('#viewcontent .products-view').remove();
                      $('#viewcontent').append(data);
                      }
                    });
        });
        ";

        $view->registerJs(implode("\n", $js));
    }
}


