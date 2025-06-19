<!-- HTML Document -->
<div class="bbn-block bbn-spadding">
  <div class="bbn-w-100 bbn-nowrap">
    <a class="bbn-b bbn-m"
       :href="link"
       bbn-text="source.name"/>
    <div class="bbn-tertiary-text-alt">
      <span bbn-if="source.last_check">
        <?= _("Info date") ?>
        <span bbn-text="fdate(source.last_check)"/>
      </span>
      <span bbn-else>
        <?= _("The info has never been built") ?>
      </span>
    </div>
  </div>
</div>