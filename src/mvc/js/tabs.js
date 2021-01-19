// Javascript Document
(() => {
  return {
    props: ['source'],
    data(){
      return {
        test: 1
      };
    },
    created(){
      appui.databases = this;
    }
  };
})();