<div class="bbn-overlay bbn-flex-height appui-database-sync-structures">
  <div class="bbn-header bbn-padding bbn-flex-width bbn-vmiddle">
    <div class="bbn-flex-fill bbn-vmiddle"></div>
    <div class="bbn-vmiddle">
      <span v-if="lastReception"><?= _('Tables list loaded on') ?></span>
      <span v-else><?= _('Tables not yet loaded') ?></span>
      <i v-if="currentLastReceptionDate"
         class="nf nf-fa-calendar bbn-right-xsspace bbn-left-sspace"/>
      <span v-if="currentLastReceptionDate"
            v-text="currentLastReceptionDate"/>
      <i v-if="currentLastReceptionTime"
         class="nf nf-oct-clock bbn-right-xsspace bbn-left-sspace"/>
      <span v-if="currentLastReceptionTime"
            v-text="currentLastReceptionTime"/>
    </div>
  </div>
  <div class="bbn-flex-fill">
    <bbn-table :source="root + 'data/sync/structures'"
               ref="table"
               :pageable="false">
      <bbns-column field="table"
                   title="<?= _('Table') ?>"/>
      <bbns-column field="last"
                   type="datetime"
                   title="<?= _('Last check') ?>"
                   :width="150"
                   cls="bbn-c"
                   :render="renderLast"/>
      <bbns-column v-for="(db, i) in source.dbs"
                   :field="db"
                   :title="db"
                   :key="i"
                   :component="$options.components.compare"
                   :width="150"
                   cls="bbn-c"
                   :options="{
                     field: db
                   }"/>
      <bbns-column :buttons="[{
                     text: '<?= _('Refresh') ?>',
                     icon: 'nf nf-fa-refresh',
                     notext: true,
                     action: refreshFile
                   }]"
                   :width="50"
                   cls="bbn-c"/>
    </bbn-table>
  </div>
</div>