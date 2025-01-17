<div class="bbn-w-100">
  <div class="bbn-w-100 bbn-header bbn-no-border bbn-c bbn-b bbn-upper bbn-spadding"
       bbn-text="source.table"/>
  <div class="bbn-w-100">
    <bbn-table :source="tableSource"
               :scrollable="false"
              no-data="<?= _("The record isn't present") ?>">
      <bbns-column field="field"
                   label="<?= _('Field') ?>"
                   :fixed="true"
                   :width="170"/>
      <bbns-column :field="source.origin.db"
                   :label="source.origin.db"
                   :fixed="true"
                   :width="350"
                   :render="source.json ? undefined : renderField"
                   :component="source.json ? $options.components.json : undefined"
                   :options="{db: source.origin.db}"/>
      <bbns-column bbn-for="(c, i) in source.currents"
                   :key="i"
                   :field="c.db"
                   :label="c.db"
                   :cls="row => isSame(row[source.origin.db], row[c.db]) ? 'bbn-bg-green bbn-white' : 'bbn-bg-red bbn-white'"
                   :width="350"
                   :render="source.json ? undefined : renderField"
                   :component="source.json ? $options.components.json : undefined"
                   :options="{db: c.db}"/>
    </bbn-table>
  </div>
</div>