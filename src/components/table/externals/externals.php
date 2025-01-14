<div class="bbn-overlay">
  <bbn-table :source="tableSource"
             :sortable="true"
             :filterable="true"
             :info="true" 
  >
    <bbns-column field="table"
                 label="<?= _('Table') ?>"
                 cls="bbn-c">
     </bbns-column>
     <bbns-column field="column"
                 label="<?= _('Column') ?>"
                 cls="bbn-c">
     </bbns-column>
     <bbns-column field="link"
                 label="<?= _('Link in table') ?>"
                 cls="bbn-c">
     </bbns-column>
  </bbn-table>
</div>