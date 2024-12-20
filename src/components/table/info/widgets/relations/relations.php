<!-- HTML Document -->

<div class="bbn-padding">
  <div class="bbn-state-error bbn-c"
       bbn-if="!source.externals && !source.constraints">
    <?= _("No relations") ?>
  </div>
  <div class="bbn-w-100 bbn-c"
       bbn-else>
    <h3><?= _("Internal constraints") ?></h3>
    <div bbn-if="source.constraints">
      <div bbn-for="(c, n) in source.constraints">
        <span bbn-text="n"/> <i class="nf nf-fa-arrow_right"/>
        <span bbn-text="c.table"
              class="bbn-b"/><br>
      </div>
    </div>
    <h4 bbn-else><?= _("No internal constraints") ?></h4>

    <h3><?= _("External relations") ?></h3>
    <div bbn-if="source.externals">
      <div bbn-for="(c, n) in source.externals">
        <div bbn-for="(d, m) in c">
          <span bbn-text="m"/> <em>(<span bbn-text="n"/>)</em><br>
        </div>
      </div>
    </div>
    <h4 bbn-else><?= _("No external relations") ?></h4>
  </div>
</div>
