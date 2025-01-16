<div class="bbn-overlay">
  <div bbn-if="!ready" class="bbn-overlay bbn-middle">
    <bbn-loader></bbn-loader>
  </div>
  <div class="bbn-overlay bbn-middle"
       bbn-else-if="currentData?.error">
    <div class="bbn-block bbn-padding bbn-shadow bbn-state-error bbn-lg bbn-xlmargin"
         bbn-text="currentData.error"/>
  </div>
  <bbn-splitter bbn-else
                :orientation="orientation"
                :resizable="true"
                :collapsible="true">
    <bbn-pane :resizable="true"
              :size="250">
      <div class="bbn-100 bbn-lg">
        <div class="bbn-header bbn-spadding">
          <bbn-button icon="nf nf-md-export"
                      :notext="true"
                      @click="exportDb"/>
        </div>
        <div class="bbn-w-100 bbn-spadding bbn-c">
          <?= _("Engine") ?>
        </div>
        <div class="bbn-w-100 bbn-b bbn-alt-background bbn-spadding bbn-c"
             bbn-text="currentData.engine"/>

        <div class="bbn-w-100 bbn-m bbn-spadding bbn-c">
          <?= _("Host") ?>
        </div>
        <div class="bbn-w-100 bbn-b bbn-alt-background bbn-spadding bbn-c"
             bbn-text="currentData.host"/>

        <div class="bbn-w-100 bbn-spadding bbn-c"
             bbn-if="currentData.ip && (currentData.host !== currentData.ip)">
          <?= _("Ip") ?>
        </div>
        <div class="bbn-w-100 bbn-b bbn-alt-background bbn-spadding bbn-c"
             bbn-if="currentData.ip && (currentData.host !== currentData.ip)"
             bbn-text="currentData.ip"/>
      </div>
    </bbn-pane>
    <bbn-pane :resizable="true">
      <div class="bbn-overlay">
        <bbn-table ref="table"
                   @toggle="checkMultipleSelected"
                   :source="currentData.dbs.data"
                   :selection="true"
                   :pageable="true"
                   :limit="50"
                   :toolbar="toolbar"
                   :info="true"
                   uid="name"
                   >
          <bbns-column field="name"
                       label="<?= _('Database') ?>"
                       component="appui-database-db-cell"/>
          <bbns-column label="<?= _("Action") ?>"
                       :width="200"
                       cls="bbn-c"
                       :component="$options.components.dropdown"/>
        </bbn-table>
      </div>
    </bbn-pane>
  </bbn-splitter>
</div>
