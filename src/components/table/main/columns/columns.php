<div class="bbn-overlay">
  <bbn-table :source="tableSource"
             :sortable="true"
             :filterable="true"
             :info="true" 
             :columns= "[{
                     field: 'position',
                     title: '<a title=\'<?=\bbn\Str::escapeSquotes(_('Position in the table'))?>\'>#</a>',
                     cls: 'bbn-c',
                     width: '40'
                   }, {
                     field: 'key',
                     title: '<i class=\'nf nf-fa-key\' title=\'<?=\bbn\Str::escapeSquotes(_('Are there keys on the column?'))?>\'></i>',
                     render: writeKeyInCol,
                     cls: 'bbn-c bbn-bg-black',
                     width: '40'
                   }, {
                     field: 'name',
                     render: writeColumn,
                     title: '<?=\bbn\Str::escapeSquotes(_('Columns'))?>',
                     cls: 'bbn-c'
                   }, {
                     field: 'type',
                     title: '<?=\bbn\Str::escapeSquotes(_('Type'))?>',
                     cls: 'bbn-c',
                     render: writeType,
                     width: '100'
                   }, {
                     field: 'maxlength',
                     title: '<?=\bbn\Str::escapeSquotes(_('Length'))?>',
                     cls: 'bbn-c',
                     width: '70'
                   }, {
                     field: 'null',
                     title: '<i class=\'nf nf-fa-ban\' title=\'<?=\bbn\Str::escapeSquotes(_('Can the field be null?'))?>\'></i>',
                     cls: 'bbn-c',
                     width: '40',
                     render: writeNull
                   }, {
                     field: 'default_value',
                     title: '<?=\bbn\Str::escapeSquotes(_('Default'))?>',
                     render: writeDefault,
                     cls: 'bbn-c',
                     width: '80'
                   }]"
  ></bbn-table>
</div>