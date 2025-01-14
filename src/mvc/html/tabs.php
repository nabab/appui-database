<!-- HTML Document -->

<bbn-router :autoload="true"
            :nav="true"
            :source="[{
               fixed: true,
               url: 'home',
               icon: '<?= $icon ?>',
               notext: true,
               label: '<?= \bbn\Str::escapeSquotes(_('List')) ?>',
               load: true,
               bcolor: '#666',
               fcolor: '#FFF'
            }]"
></bbn-router>
