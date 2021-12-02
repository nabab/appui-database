<div class="bbn-overlay">
  <bbn-table :source="tableSource"
             :sortable="true"
             :filterable="true"
             :info="true"
             v-for="(i) in tableSource"
             :columns= "[{
                        title: ' ',
                        cls: 'bbn-c',
                        width: 140,
                        buttons: [
                        {
                        text: _('Update'),
                        action: update,
                        icon: 'nf nf-fa-edit',
                        notext: true,
                        },
                        {
                        text: _('Remove'),
                        action: remove,
                        icon: 'nf nf-fa-times',
                        notext: true,
                        },
                        {
                        text: _('Move Up'),
                        action: moveUp,
                        icon: 'nf nf-fa-arrow_up',
                        notext: true,
                        },
                        {
                        text: _('Move Down'),
                        action: moveDown,
                        icon: 'nf nf-fa-arrow_down',
                        notext: true,
                        },
                        ],
                        }, {
                        field: 'position',
                        title: '<a title=\'<?=\bbn\Str::escapeSquotes(_('Position in the table'))?>\'>#</a>',
                        cls: 'bbn-c',
                        width: '40'
                        }, {
                        field: 'key',
                        title: '<i class=\'nf nf-fa-key\' title=\'<?=\bbn\Str::escapeSquotes(_('Are there keys on the column?'))?>\'></i>',
                        render: writeKeyInCol,
                        cls: 'bbn-c bbn-bg-black bbn-xl',
                        width: '40'
                        }, {
                        field: 'name',
                        render: writeColumn,
                        title: '<?=\bbn\Str::escapeSquotes(_('Columns'))?>',
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
             >
  <bbn-button :text="_('Move up')"
              :notext="true"
              @click="moveUp(i)"></bbn-button>
   <bbn-button :text="_('Move down')"
               :notext="true"
              @click="moveUp(i)"></bbn-button>
  </bbn-table>
</div>