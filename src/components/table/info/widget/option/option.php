<!-- HTML Document -->

<div class="bbn-padding">
  <div class="bbn-state-error bbn-padding bbn-c"
       bbn-if="!source.table_id">
    <?= _("No option") ?>
  </div>
  <div class="bbn-grid-fields"
       bbn-else>
    <label>
      <?= _("ID") ?>
    </label>
    <div bbn-text="source.table_id"/>

    <label>
      <?= _("Title") ?>
    </label>
    <bbn-editable bbn-model="source.option.text"
                  @save="saveTitle"
                  :required="true"/>

    <label>
      <?= _("Item viewer") ?>
      <bbn-tooltip source="<?= _("A component which will be used to show an item from this table in other lists or widgets") ?>"/>
    </label>
    <div>
      <span bbn-if="source.option.viewer"
            bbn-html="source.option.viewer + '<br>'"/>
      <bbn-button icon="nf nf-fa-edit"
                  :label="source.option.viewer ? _('Change') : _('Set')"
                  @click="browseItemViewer"/>
      <bbn-button bbn-if="source.option.viewer"
                  icon="nf nf-fa-trash"
                  :label="_('Unset')"
                  @click="saveViewer(null, source.option.viewer)"/>
    </div>

    <label>
      <?= _("Row editor") ?>
      <bbn-tooltip source="<?= _("A component which will be used to edit a whole item from this table") ?>"/>
    </label>
    <div>
      <span bbn-if="source.option.editor"
            bbn-html="source.option.editor + '<br>'"/>
      <bbn-button icon="nf nf-fa-edit"
                  :label="source.option.editor ? _('Change') : _('Set')"
                  @click="browseRowEditor"/>
      <bbn-button bbn-if="source.option.editor"
                  icon="nf nf-fa-trash"
                  :label="_('Unset')"
                  @click="saveEditor(null, source.option.editor)"/>
    </div>

    <label>
      <?= _("Display columns") ?>
      <bbn-tooltip source="<?= _("Pick the column(s) that you want to see from this table in other lists") ?>"/>
    </label>
    <div>
      <span bbn-if="hasDisplayedColumns"
            bbn-html="displayedColumnsStr"/>
      <bbn-button icon="nf nf-fa-edit"
                  :label="source.option.dcolumns?.length ? _('Change') : _('Set')"
                  @click="setDisplayColumns"/>
    </div>
  </div>
</div>
