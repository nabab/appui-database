<div class="bbn-overlay">
  <bbn-table :source="tableSource"
             :sortable="true"
             :filterable="true"
             :info="true"
             button-mode="menu"
             :columns= "[
                        {
                          title: ' ',
                          cls: 'bbn-c',
                          width: 40,
                          buttons: [
                            {
                              text: _('Update'),
                              action: update,
                              icon: 'nf nf-fa-edit'
                            },
                            {
                              text: _('Remove'),
                              action: remove,
                              icon: 'nf nf-fa-times'
                            },
                            {
                              text: _('Move Up'),
                              action: moveUp,
                              icon: 'nf nf-fa-arrow_up'
                            },
                            {
                              text: _('Move Down'),
                              action: moveDown,
                              icon: 'nf nf-fa-arrow_down'
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
  </bbn-table>
</div>