<!-- HTML Document -->

<bbn-router :autoload="true"
            :nav="true"
            :source="[{
               static: true,
               url: 'home',
               icon: '<?=$icon?>',
               notext: true,
               title: '<?=\bbn\Str::escapeSquotes(_('List'))?>',
               load: true,
               bcolor: '#666',
               fcolor: '#FFF'
            }]"
></bbn-router>