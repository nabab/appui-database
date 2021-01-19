<!-- HTML Document -->
<bbn-form :source="formData"
          ref="form"
          :validation="() => checked === 1"
          :action="root + '/actions/host/add'"
          @success="success"
          confirm-leave="<?=_("Are you sure you want to leave this form without saving your changes?")?>">
  <div class="bbn-grid-fields bbn-padded">
    <div><?=_('Host')?></div>
    <bbn-input v-model="formData.host"
               name="host"
               :required="true"
               @change="changeHost"
    />

    <div><?=_('Name')?></div>
    <bbn-input v-model="formData.name"
               name="name"
               :required="true"/>

    <fieldset class="bbn-grid-full" v-if="source.engine !== 'sqlite'">
      <legend><?=_('Administrator')?></legend>
      <div class="bbn-grid-fields">
        <span><?=_('Username')?></span>
        <bbn-input v-model="formData.username"
               		 name="username"
                   :required="true"
                   @change="checkConnection"/>

        <span><?=_('Password')?></span>
        <bbn-input v-model="formData.password"
               		 name="password"
                   :required="true"
                   type="password"
                   @change="checkConnection"/>
      </div>
    </fieldset>
  </div>
</bbn-form>
