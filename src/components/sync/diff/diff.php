<div class="bbn-w-100">
  <div  v-for="(c, i) in source.currents"
        :class="['bbn-w-100', {'bbn-bottom-space': source.currents[i+1]}]">
    <div class="bbn-header bbn-xspadded bbn-c bbn-b"><?=_('DATABASE')?>: <span v-text="c.db"></span></div>
    <bbn-table :source="getSource(c)"
               :scrollable="false"
               no-data="<?=_("The record isn't present")?>"
    >
      <bbns-column field="field"
                   title="<?=_('Field')?>"
                   :cls="row => row.same ? 'bbn-bg-green bbn-white' : 'bbn-bg-red bbn-white'">
      </bbns-column>
      <bbns-column field="value"
                   :title="source.origin.db"
                   :cls="row => row.same ? 'bbn-bg-green bbn-white' : 'bbn-bg-red bbn-white'">
      </bbns-column>
      <bbns-column field="db"
                   :title="c.db"
                   :cls="row => row.same ? 'bbn-bg-green bbn-white' : 'bbn-bg-red bbn-white'">
      ></bbns-column>
    </bbn-table>
  </div>
</div>