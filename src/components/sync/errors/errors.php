<!-- HTML Document -->
<div class="bbn-overlay">
  <bbn-table :source="root + 'data/sync/errors'"
            :pageable="true"
            :sortable="true"
            :order="[{field: 'dt', dir: 'ASC'}]"
            :filterable="true"
            :expander="$options.components.expander"
            ref="table"
            :selection="true"
            uid="id"
            @hook:mounted="setWatch"
            :toolbar="[{
              text: '<?= _('Fix') ?>',
              icon: 'nf nf-mdi-auto_fix',
              action: fixSelected,
              disabled: !selected.length
            }, {
              text: '<?= _('Delete') ?>',
              icon: 'nf nf-fa-trash',
              action: removeSelected,
              disabled: !selected.length
            }, {
              text: '<?= _('Scan & clear') ?>',
              icon: 'nf nf-mdi-washing_machine',
              action: scanClear
            }]">
    <bbns-column field="db"
                :label="_('From')"
                :source="source.dbs"
                :width="100"/>
    <bbns-column field="dt"
                :label="_('Date')"
                type="datetime"
                :width="140"
                :render="renderDate"/>
    <bbns-column field="action"
                :label="_('Operation')"
                :source="['INSERT', 'UPDATE', 'DELETE']"
                :width="100"/>
    <bbns-column field="tab"
                :label="_('Table')"
                :source="source.tables"/>
    <bbns-column field="vals"
                :render="renderVals"
                :label="_('Values')"
                :filterable="false"
                :sortable="false"/>
    <bbns-column field="diff"
                :render="renderDiff"
                :label="_('Diff')"
                type="boolean"
                :sortable="false"
                cls="bbn-c"
                :width="60"
                :source="[{
                  text: _('Yes'),
                  value: true
                }, {
                  text: _('No'),
                  value: false
                }]"/>
    <bbns-column :label="_('Actions')"
                :width="170"
                :buttons="getButtons"
                clas="bbn-c"/>
  </bbn-table>
</div>