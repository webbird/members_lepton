<?php
	//Solving Problem: if position DESC: Reverse Sorting
	$moveuplink = LEPTON_URL.'/modules/members/move_up.php?';
	$movedownlink = LEPTON_URL.'/modules/members/move_down.php?';
		
	if ($sort_by == 'position DESC') {
	//reverse:	
		$moveuplink = LEPTON_URL.'/modules/members/move_down.php?';
		$movedownlink = LEPTON_URL.'/modules/members/move_up.php?';	
	}
	
		
	while($members = $query_members->fetchRow()) {
	$isalias = 0 + (int)$members['m_isalias'];
	$member_id = 0 + (int)$members['member_id'];
	$countmembers++; 
	if ($isalias > 0) $countalias++;
		?>
		<tr class="mrow<?php if($isalias > 0) echo " alias";  if($member_id == $hlmember) echo " hilite"; ?>" onmouseover="this.style.backgroundColor = '#F1F8DD'" onmouseout="this.style.backgroundColor = '#ffffff'">
			<td class="membertd1">			
				<a href="<?php echo $mod_ml.$mod_param.$group_id.'&member_id='.$member_id.'"><img src="'.$picurl.'mod'; if ($isalias == 0) {echo 'm';} else {echo 'a';}?>.gif" alt="Modify" /></a>
				<?php if ($use_aliases == 1) { 
					if ($isalias == 0) { echo '<a href="'.$add_ml.$mod_param.$group_id.'&addalias='.$member_id.'"><img src="'.$picurl.'addalias.gif" border="0" alt="Add Alias" /></a>';} else { echo '<a href="'.$add_ml.$mod_param.$group_id.'&addalias='.$isalias.'"><img src="'.$picurl.'isalias.gif" border="0" alt="Is Alias" /></a>';} 
				} else { 
					echo '<img src="'.$picurl.'blind.gif" border="0" alt="" />'; 					
				} ?>
			</td>
			<td class="membertd2">
				<a href="<?php echo LEPTON_URL; ?>/modules/members/modify_member.php?<?php echo $mod_param.$group_id; ?>&member_id=<?php echo $member_id; ?>">
					<?php 
					if ($isalias == 0) {  //is NO alias, search for aliases od this member
						echo '<span class="ismember">'.stripslashes($members['m_name']).'</span>'; 
						$query_alias = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_members` WHERE m_isalias = '$member_id'");
						$has_alias = $query_alias->numRows();
						if($has_alias > 0) {
							$countaliasofmembers += $has_alias;
							echo ' <span class="has_alias">('.$has_alias.'*)</span>';							
						}
					} else { echo '<span class="isalias">'.stripslashes($members['m_name']).'</span>'; }?>
				</a>
			</td>			
			<td><img src="<?php echo $picurl.'mactive'.$group_active.$members['active'].'.gif" alt="'.$TEXT['ACTIVE'].': '; if($members['active'] == 1) { echo $TEXT['YES']; } else { echo $TEXT['NO']; } ?>" /></td>
			<?php if ($sort_mem_name > 0) {  
				$t = 'class="score_td"> &nbsp;';
				if ($sort_mem_name == 2) {$t='class="sort_td">'.$members['m_sortt'];}
				if ($sort_mem_name == 3) {$t='class="score_td">'.$members['m_score'];}
				echo '<td colspan="2" '.$t.'</td>';
			} else { ?>
			<td><?php if ($countmembers == 1){ echo '&nbsp'; } else {
					echo '<a href="'.$moveuplink.$mod_param.$group_id.'&member_id='.$member_id.'" name="'.$TEXT['MOVE_UP'].'"><img src="'.LEPTON_URL.'/modules/lib_lepton/backend_images/up_16.png" border="0" alt="^" /></a>';
				} ?>
			</td>
			<td><?php if ($countmembers == $countquery_members){ echo '&nbsp'; } else {
					echo '<a href="'.$movedownlink.$mod_param.$group_id.'&member_id='.$member_id.'" name="'.$TEXT['MOVE_DOWN'].'"><img src="'.LEPTON_URL.'/modules/lib_lepton/backend_images/down_16.png" border="0" alt="v" /></a>';
				} ?>
			</td>
			<?php } ?>
			<td>
				<a href="#" onclick="javascript: confirm_link('<?php echo $TEXT['ARE_YOU_SURE']; ?>', '<?php echo LEPTON_URL; ?>/modules/members/ghost_member.php?<?php echo $mod_param.$group_id; ?>&member_id=<?php echo $member_id; ?>');" name="<?php echo $TEXT['DELETE']; ?>">
					<img src="<?php echo $picurl.'ghost.gif" alt="'. $TEXT['DELETE']; ?>" />
				</a>
			</td>
		</tr>
		
		
		<?php		
	} ?>