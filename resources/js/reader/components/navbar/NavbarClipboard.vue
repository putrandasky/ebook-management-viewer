<template>
  <b-nav-item @click="handleCliboard"><i class="fa fa-link"></i>
    <slot></slot>
  </b-nav-item>

</template>
<script>
  export default {
    name: 'NavbarClipboard',
    data: function() {
      return {}
    },
    created() {},
    methods: {

      handleCliboard() {
        let protocol = window.location.protocol + '//'
        let host = window.location.host
        let pathname = window.location.pathname
        let shareAbleLink = protocol + host + pathname
        this.copyTextToClipboard(shareAbleLink)
        this.buttonCopyText = '<i class="fa fa-check"></i> Copied!'
        setTimeout(() => {
          this.buttonCopyText = "Click to Copy"
        }, 500);
      },
      fallbackCopyTextToClipboard(text) {
        var textArea = document.createElement("textarea");
        textArea.value = text;

        // Avoid scrolling to bottom
        textArea.style.top = "0";
        textArea.style.left = "0";
        textArea.style.position = "fixed";

        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
          var successful = document.execCommand('copy');
          var msg = successful ? 'successful' : 'unsuccessful';
          console.log('Fallback: Copying text command was ' + msg);
        } catch (err) {
          console.error('Fallback: Oops, unable to copy', err);
        }

        document.body.removeChild(textArea);
      },

      copyTextToClipboard(text) {
        if (!navigator.clipboard) {
          this.fallbackCopyTextToClipboard(text);
          return;
        }
        let self = this
        navigator.clipboard.writeText(text).then(function() {
          console.log('Async: Copying to clipboard was successful!');
          self.$bvToast.toast('Current Link Address Copied', {
            title: `SUCCESS!`,
            headerClass: 'font-weight-bold',
            variant: 'success',
            solid: true,
            autoHideDelay: 1000,
            toaster: 'b-toaster-top-right'
          })
        }, function(err) {
          console.error('Async: Could not copy text: ', err);
        });
      }
    },
  }
</script>
<style>
</style>
