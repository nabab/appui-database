<div class="appui-database-table-keys bbn-overlay">
  <bbn-table :source="tableSource"
             :sortable="true"
             button-mode="menu"
             :scrollable="true"
             ref="table"
             :selection="true"
             @toggle="onTableToggle"
             :tr-class="trClass">
    <bbns-column cls="bbn-c"
                 :buttons="buttons"
                 width="40"/>
    <bbns-column field="name"
                 label="<?= _('Name') ?>"
                 cls="bbn-c"/>
    <bbns-column field="columns"
                 :render="renderColumns"
                 label="<?= _('Columns') ?>"
                 cls="bbn-c"/>
    <bbns-column field="constraint"
                 :render="renderConstraint"
                 label="<?= _('Constraint') ?>"
                 cls="bbn-c"/>
    <bbns-column field="unique"
                 type="boolean"
                 label="<?= _('Unique') ?>"
                 cls="bbn-c"
                 width="80"/>
    <bbns-column field="is_real"
                  label="<i class='nf nf-cod-database'></i>"
                  full-label="<?= _("Exists in the host") ?>"
                  :render="renderRealVirtual"
                  :width="30"
                  cls="bbn-c"/>
    <bbns-column field="is_virtual"
                  label="<i class='nf nf-md-opera'></i>"
                  full-label="<?= _("Exists as options") ?>"
                  :render="renderRealVirtual"
                  :width="30"
                  cls="bbn-c"/>
  </bbn-table>
</div>