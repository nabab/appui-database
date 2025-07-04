<!-- HTML Document -->

<div class="appui-database-table-create bbn-spadding bbn-c bbn-w-100">
  <bbn-form :source="formData"
            ref="form"
            :scrollable="false"
            :action="root + 'actions/table/create'"
            :data="tableData"
            :validation="() => formData.name && formData.columns.length && (edited === -1)"
            :confirm-message="_('Are you sure you are ready to create the table?')"
            @keydown.esc.stop.prevent
            @success="onCreate">
    <div class="bbn-flex-width"
         style="column-gap: var(--sspace)">
      <div class="bbn-radius bbn-background-tertiary bbn-tertiary-text"
           style="min-width: 30rem">
        <div class="bbn-middle bbn-upper bbn-b bbn-spadding bbn-border-bottom bbn-lg">
          <?= _("Table information") ?>
        </div>
        <div class="bbn-grid-fields bbn-nowrap bbn-padding">
          <label><?= _("Table's name") ?></label>
          <div>
            <bbn-input class="bbn-w-100"
                       bbn-model="formData.name"
                       :required="true"/>
          </div>
          <label><?= _("Charset") ?></label>
          <div>
            <bbn-dropdown class="bbn-w-100"
                          bbn-model="formData.charset"
                          :source="[]"/>
          </div>
          <label><?= _("Collation") ?></label>
          <div>
            <bbn-dropdown class="bbn-w-100"
                          bbn-model="formData.collation"
                          :source="[]"/>
          </div>
          <label><?= _("Comment") ?></label>
          <div>
            <bbn-input class="bbn-w-100"
                       bbn-model="formData.comment"/>
          </div>
          <label><?= _("Options") ?></label>
          <div>
            <bbn-switch bbn-model="formData.options"
                        :value="true"
                        :novalue="false"/>
          </div>
        </div>
        <div bbn-if="formData.columns?.length"
             class="bbn-middle bbn-upper bbn-b bbn-spadding bbn-border-bottom bbn-m bbn-hspace">
          <?=_("Columns")?>
        </div>
        <div bbn-if="formData.columns?.length"
             class="bbn-grid-fields bbn-nowrap bbn-padding">
          <template bbn-for="(col, i) in formData.columns">
            <div class="bbn-nowrap bbn-s">
              <span class="bbn-right-smargin">
                <i class="nf nf-md-key_variant"
                   :style="{visibility: formData.columns[i].constraint ? 'visible' : 'hidden'}"/>
              </span>
              <bbn-button :label="_('Move up')"
                          icon="nf nf-fa-long_arrow_up"
                          :notext="true"
                          @click="moveUp(i)"
                          :disabled="(edited !== -1) || (i === 0) || (numMovableColumns <= 1)"/>
              <bbn-button :label="_('Move down')"
                          icon="nf nf-fa-long_arrow_down"
                          :notext="true"
                          @click="moveDown(i)"
                          :disabled="(edited !== -1) || (i === numMovableColumns - 1) || (numMovableColumns <= 1)"/>
              <bbn-button :label="_('Edit')"
                          icon="nf nf-fa-edit"
                          :notext="true"
                          @click="edited = i"
                          :disabled="edited !== -1"/>
              <bbn-button :label="_('Remove')"
                          icon="nf nf-fa-trash"
                          :notext="true"
                          @click="removeColumn(i)"
                          :disabled="edited !== -1"/>
            </div>
            <div bbn-html="getColDescription(col)"
                 class="bbn-s"/>
          </template>
        </div>
      </div>
      <div class="bbn-flex-fill bbn-background-secondary bbn-secondary-text bbn-radius bbn-grid"
           style="grid-auto-rows: max-content auto; min-width: 30rem">
        <div class="bbn-middle bbn-upper bbn-b bbn-spadding bbn-border-bottom bbn-lg">
          <?= _("Column definition") ?>
        </div>
        <div bbn-if="edited > -1"
             class="bbn-padding">
          <appui-database-column-form :source="formData.columns[edited]"
                                      :otypes="source.types"
                                      :engine="source.engine"
                                      :host="source.host"
                                      :db="source.db"
                                      :predefined="source.predefined"
                                      :constraints="source.constraints"
                                      @cancel="onCancel"
                                      @change="onChange"/>
        </div>
        <div bbn-else
             class="bbn-padding bbn-middle">
            <bbn-button @click="addColumn()"
                        :label="_('Add a new column')"
                        icon="nf nf-fa-plus"/>

          </div>
      </div>
    </div>
  </bbn-form>
</div>