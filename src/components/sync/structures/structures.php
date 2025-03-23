<div class="bbn-overlay bbn-flex-height appui-database-sync-structures">
  <div class="bbn-header bbn-padding bbn-flex-width bbn-vmiddle">
    <div class="bbn-flex-fill bbn-vmiddle"></div>
    <div class="bbn-vmiddle">
      <span bbn-if="lastReception"><?= _('Tables list loaded on') ?></span>
      <span bbn-else><?= _('Tables not yet loaded') ?></span>
      <i bbn-if="currentLastReceptionDate"
         class="nf nf-fa-calendar bbn-right-xsspace bbn-left-sspace"/>
      <span bbn-if="currentLastReceptionDate"
            bbn-text="currentLastReceptionDate"/>
      <i bbn-if="currentLastReceptionTime"
         class="nf nf-oct-clock bbn-right-xsspace bbn-left-sspace"/>
      <span bbn-if="currentLastReceptionTime"
            bbn-text="currentLastReceptionTime"/>
    </div>
  </div>
  <div class="bbn-flex-fill">
    <bbn-table :source="root + 'data/sync/structures'"
               ref="table"
               :pageable="false">
      <bbns-column field="table"
                   label="<?= _('Table') ?>"/>
      <bbns-column field="last"
                   type="datetime"
                   label="<?= _('Last check') ?>"
                   :width="150"
                   cls="bbn-c"
                   :render="renderLast"/>
      <bbns-column bbn-for="(db, i) in source.dbs"
                   :field="db"
                   :label="db"
                   :key="i"
                   :component="$options.components.compare"
                   :width="150"
                   cls="bbn-c"
                   :options="{
                     field: db
                   }"/>
      <bbns-column :buttons="[{
                     label: '<?= _('Refresh') ?>',
                     icon: 'nf nf-fa-refresh',
                     notext: true,
                     action: refreshFile
                   }]"
                   :width="50"
                   cls="bbn-c"/>
    </bbn-table>
  </div>
</div>