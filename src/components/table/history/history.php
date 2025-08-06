<div :class="['appui-database-table-history', 'bbn-padding', 'bbn-overlay', {'bbn-middle': !source.history}]">
  <div bbn-if="!source.history"
       class="bbn-radius bbn-padding bbn-background-secondary bbn-secondary-text bbn-upper bbn-lg">
    <div><?=_("This table does not use the History system")?></div>
    <bbn-button label="<?=_("Click here to integrate it")?>"
                icon="nf nf-md-creation"
                @click="integrateHistory"
                class="bbn-top-space"/>
  </div>
</div>