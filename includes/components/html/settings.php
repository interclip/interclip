<dark-mode-toggle id="dark-mode-toggle-1"></dark-mode-toggle>

<!-- The Modal -->
<div id="settingsModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="closeBtn">&times;</span>
    <h2>Settings</h2>
    <p>Here you can change some of Interclip's settings.</p>
    <h3>Color scheme</h3>
    <div class="select">
      <select name="slct" id="slct" class="slct">
        <option value="dark">Dark 🌑</option>
        <option value="light">Light ☀️</option>
        <option id="systemOption" value="system">System</option>
      </select>
    </div>
    <h3>Default file server</h3>
    <div class="select">
      <select name="slct" id="file-slct" class="slct">
        <option value="ipfs" disabled>IPFS 🌍</option>
        <option value="iclip">Interclip 💾</option>
      </select>
    </div>
    <h3>Hash animations</h3>
    <p>Toggle the animation of the random hash on the receive page.</p>
    <div class="flex">
      <label for="hashanimation" class="gui-switch">
        Off
        <input type="checkbox" role="switch" id="hashanimation">
        On
      </label>
    </div>
    <h3>Beta menu</h3>
    <p>Hide or show Interclip's beta features in the menu.</p>
    <div class="flex">
      <label for="betafeatures" class="gui-switch">
        Hide
        <input type="checkbox" role="switch" id="betafeatures">
        Show
      </label>
    </div>
    <h3>Danger zone</h3>
    <p>Clear all the local data including settings and recently made clips.</p>
    <button class="btn-remove" id="removeData">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
      </svg>
      <span>
        Remove all data
      </span>
    </button>
  </div>

</div>