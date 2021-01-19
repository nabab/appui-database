(() => {
  return {
    created(){
      appui.$on('appui-databases', (type, data) => {
        if (type === 'message') {
          if ('sync' in data) {
            let conflictsComp = this.find('appui-databases-sync-conflicts');
            if (bbn.fn.isVue(conflictsComp) && bbn.fn.isFunction(conflictsComp.receive)) {
              conflictsComp.receive(data.sync);
            }
          }
        }
      });
      if (!('appui-databases' in appui.pollerObject)) {
        appui.$set(appui.pollerObject, 'appui-databases', {
          sync: {
            conflictsHash: this.source.filesHash
          }
        });
      }
      else {
        appui.$set(appui.pollerObject['appui-databases'], 'sync', {
          conflictsHash: this.source.filessHash
        });
      }
      appui.poll();
    },
    beforeDestroy(){
      appui.$off('appui-databases');
    }
  }
})();