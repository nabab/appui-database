<div class="bbn-overlay">
  <bbn-table :source="tableSource"
             :sortable="true"
             :filterable="true"
             :info="true" 
  >
    <bbns-column field="name"
                 title="<?=_('Keys')?>"
                 :render="writeKey"
                 cls="bbn-c">
    </bbns-column>
    <bbns-column field="columns"
                 :render="writeColInKey"
                 title="<?=_('Columns')?>"
                 cls="bbn-c">
    </bbns-column>
    <bbns-column field="constraint"
                 :render="writeConstraint"
                 :filterable="false"
                 title="<?=_('Constraint')?>"
                 cls="bbn-c">
    </bbns-column>
    <bbns-column field="unique"
                 type="boolean"
                 title="<?=_('Unique')?>"
                 cls="bbn-c"
                 width="80">
    </bbns-column>
    <bbns-column title="<?=_('Actions')?>"
                 cls="bbn-c"
                 :buttons="buttons"
                 width="200">
    </bbns-column>
  </bbn-table>
</div>