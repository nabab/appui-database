<bbn-table :source="tableSource"
           :scrollable="true"
           no-data="<?=_("The record isn't present")?>"
>
  <bbns-column field="field"
               title="<?=_('Field')?>"
               :fixed="true"
               :width="170"/>
  <bbns-column :field="source.origin.db"
               :title="source.origin.db"
               :fixed="true"
               :width="350"/>
  <bbns-column v-for="(c, i) in source.currents"
               :key="i"
               :field="c.db"
               :title="c.db"
               :cls="row => isSame(row[source.origin.db], row[c.db]) ? 'bbn-bg-green bbn-white' : 'bbn-bg-red bbn-white'"
               :width="350"/>
</bbn-table>