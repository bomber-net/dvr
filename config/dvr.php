<?php
return
	[
		'tmpDir'=>env ('TMP_DIR',storage_path ('logs')),
		'outDir'=>env ('OUT_DIR',storage_path ('app/private')),
	];
