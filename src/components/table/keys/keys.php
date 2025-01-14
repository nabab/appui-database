<div class="bbn-overlay">
  <bbn-table :source="tableSource"
             :sortable="true"
             :filterable="true"
             :info="true" 
  >
    <bbns-column field="name"
                 label="<?= _('Keys') ?>"
                 :render="writeKey"
                 cls="bbn-c">
    </bbns-column>
    <bbns-column field="columns"
                 :render="writeColInKey"
                 label="<?= _('Columns') ?>"
                 cls="bbn-c">
    </bbns-column>
    <bbns-column field="constraint"
                 :render="writeConstraint"
                 :filterable="false"
                 label="<?= _('Constraint') ?>"
                 cls="bbn-c">
    </bbns-column>
    <bbns-column field="unique"
                 type="boolean"
                 label="<?= _('Unique') ?>"
                 cls="bbn-c"
                 width="80">
    </bbns-column>
    <bbns-column label="<?= _('Actions') ?>"
                 cls="bbn-c"
                 :buttons="buttons"
                 width="200">
    </bbns-column>
  </bbn-table>
</div>